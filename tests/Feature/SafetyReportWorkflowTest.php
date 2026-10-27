<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureDashboardSafetyAccess;
use App\Models\SafetyReport;
use App\Models\SafetyReportEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class SafetyReportWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_submit_report_with_required_preflight_answers_and_photo(): void
    {
        Storage::fake('local');
        $this->verifyEmployeeIdentity();

        $response = $this->post(route('safety-reports.store'), [
            'reporter_name' => 'Nama yang tidak dipercaya',
            'employee_number' => '99999',
            'section' => 'Assembly',
            'location' => 'Line 1',
            'hazard_description' => 'Kabel terkelupas di jalur pejalan kaki.',
            'reported_to_supervisor' => 'belum',
            'ky_created' => 'sudah',
            'image' => UploadedFile::fake()->image('hazard.jpg'),
        ]);

        $report = SafetyReport::query()->firstOrFail();

        $response->assertRedirect(route('safety-reports.confirmation'));
        $response->assertSessionHas('submitted_report_number', $report->report_number);
        $this->assertSame('awaiting_pic', $report->status);
        $this->assertSame('Budi Santoso', $report->reporter_name);
        $this->assertSame('12345', $report->employee_number);
        $this->assertFalse($report->reported_to_supervisor);
        $this->assertTrue($report->ky_created);
        $this->assertStringStartsWith('SH-', $report->report_number);
        $this->assertSame('', $report->initial_image_path);
        $this->assertNotEmpty($report->initial_image_data);
        $this->assertDatabaseHas('safety_report_employees', [
            'nrp' => '12345',
            'name' => 'Budi Santoso',
        ]);
        $this->assertDatabaseHas('safety_report_events', [
            'safety_report_id' => $report->id,
            'event_type' => 'submitted',
            'to_status' => 'awaiting_pic',
        ]);

        $this->get(route('safety-reports.confirmation'))
            ->assertOk()
            ->assertSee($report->report_number)
            ->assertSee('Ringkasan laporan')
            ->assertSee('Budi Santoso')
            ->assertSee('Kabel terkelupas di jalur pejalan kaki.')
            ->assertSee('Menunggu verifikasi PIC');
    }

    public function test_report_submission_requires_both_preflight_answers_and_a_photo(): void
    {
        $this->verifyEmployeeIdentity();

        $response = $this->from(route('safety-reports.create'))
            ->post(route('safety-reports.store'), [
                'section' => 'Assembly',
                'location' => 'Line 1',
                'hazard_description' => 'Kabel terkelupas di jalur pejalan kaki.',
            ]);

        $response->assertRedirect(route('safety-reports.create'));
        $response->assertSessionHasErrors(['reported_to_supervisor', 'ky_created', 'image']);
        $this->assertDatabaseCount('safety_reports', 0);
    }

    public function test_report_form_shows_questions_sequentially_and_hides_the_popup_when_continuing(): void
    {
        $this->get(route('safety-reports.create'))
            ->assertOk()
            ->assertSee('id="npk"', false)
            ->assertDontSee('id="question-supervisor"', false);

        $this->verifyEmployeeIdentity();

        $this->get(route('safety-reports.create'))
            ->assertOk()
            ->assertDontSee('id="npk"', false)
            ->assertSee('id="question-supervisor"', false)
            ->assertSee('id="question-ky" class="mb-3" hidden', false)
            ->assertSee('Silakan lapor kepada atasan terlebih dahulu.')
            ->assertSee('Silakan mengisi Kiken Yochi (KY) terlebih dahulu.')
            ->assertSee('id="continue-report"', false)
            ->assertSee('preflight.hidden = true;', false)
            ->assertSee('#preflight[hidden]', false);
    }

    public function test_employee_identity_must_match_an_active_employee_before_questions_are_shown(): void
    {
        $this->registerEmployee();

        $this->post(route('safety-reports.verify-employee'), [])
            ->assertRedirect('/')
            ->assertSessionHas('employee_verification_error', 'NPK dan nama wajib diisi sesuai data karyawan.');

        $this->post(route('safety-reports.verify-employee'), [
            'npk' => '99999',
            'name' => 'Budi Santoso',
        ])
            ->assertRedirect('/')
            ->assertSessionHas('employee_verification_error', 'NPK atau nama tidak sesuai dengan data karyawan aktif.');

        $this->get('/')
            ->assertOk()
            ->assertSee('NPK atau nama tidak sesuai dengan data karyawan aktif.')
            ->assertSee('Verifikasi belum berhasil')
            ->assertSee('Coba verifikasi kembali')
            ->assertSee('employee-verification-backdrop', false);

        $this->get(route('safety-reports.create'))
            ->assertOk()
            ->assertSee('id="npk"', false)
            ->assertDontSee('id="question-supervisor"', false);
    }

    public function test_employee_name_must_match_and_inactive_employees_cannot_verify(): void
    {
        $this->registerEmployee();

        $this->post(route('safety-reports.verify-employee'), [
            'npk' => '12345',
            'name' => 'Nama tidak cocok',
        ])
            ->assertRedirect('/')
            ->assertSessionHas('employee_verification_error');

        DB::table('safety_report_employees')->where('nrp', '12345')->update(['is_active' => false]);

        $this->post(route('safety-reports.verify-employee'), [
            'npk' => '12345',
            'name' => 'Budi Santoso',
        ])
            ->assertRedirect('/')
            ->assertSessionHas('employee_verification_error');

        $this->get(route('safety-reports.create'))
            ->assertOk()
            ->assertSee('id="npk"', false)
            ->assertDontSee('id="question-supervisor"', false);
    }

    public function test_report_cannot_be_submitted_without_employee_identity_verification(): void
    {
        Storage::fake('local');

        $this->post(route('safety-reports.store'), [
            'section' => 'Assembly',
            'location' => 'Line 1',
            'hazard_description' => 'Kabel terkelupas di jalur pejalan kaki.',
            'reported_to_supervisor' => 'sudah',
            'ky_created' => 'sudah',
            'image' => UploadedFile::fake()->image('hazard.jpg'),
        ])
            ->assertRedirect(route('safety-reports.create'))
            ->assertSessionHasErrors('employee');

        $this->assertDatabaseCount('safety_reports', 0);
    }

    public function test_user_without_safety_role_cannot_open_internal_report_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('safety-reports.index'))
            ->assertForbidden();
    }

    public function test_admin_account_can_authenticate_and_open_user_management(): void
    {
        User::factory()->create([
            'email' => 'admin@cmsippi.com',
            'password' => 'test-password',
            'role' => 'admin',
        ]);

        $this->post(route('login'), [
            'email' => 'admin@cmsippi.com',
            'password' => 'test-password',
        ])->assertRedirect();

        $this->assertAuthenticated();
        $this->get(route('users.index'))->assertOk();
    }

    public function test_internal_report_list_and_detail_pages_render_for_pic(): void
    {
        $report = SafetyReport::factory()->create();

        $this->actingAs(User::factory()->create(['role' => 'safety_pic']))
            ->get(route('safety-reports.index'))
            ->assertOk()
            ->assertSee($report->report_number);

        $this->get(route('safety-reports.show', $report))
            ->assertOk()
            ->assertSee('Timeline SLA')
            ->assertSee('Verifikasi PIC');
    }

    public function test_pic_ir_and_she_can_complete_the_review_and_repair_workflow(): void
    {
        Storage::fake('local');

        $pic = User::factory()->create(['role' => 'safety_pic']);
        $ir = User::factory()->create(['role' => 'ir_reviewer']);
        $she = User::factory()->create(['role' => 'she']);

        $this->verifyEmployeeIdentity();

        $this->post(route('safety-reports.store'), [
            'section' => 'Assembly',
            'location' => 'Line 1',
            'hazard_description' => 'Kabel terkelupas di jalur pejalan kaki.',
            'reported_to_supervisor' => 'sudah',
            'ky_created' => 'belum',
            'image' => UploadedFile::fake()->image('hazard.jpg'),
        ])->assertRedirect(route('safety-reports.confirmation'));

        $report = SafetyReport::query()->firstOrFail();

        $this->actingAs($pic)
            ->post(route('safety-reports.verify', $report), [
                'checklist' => [
                    'high' => [1],
                    'medium' => [2],
                ],
                'note' => 'Lokasi perlu diamankan segera.',
                'actual_image' => UploadedFile::fake()->image('actual.jpg'),
            ])
            ->assertRedirect(route('safety-reports.show', $report));

        $report->refresh();
        $this->assertSame('awaiting_ir', $report->status);
        $this->assertSame('high', $report->risk_level);
        $this->assertNotNull($report->verification_due_at);
        $this->assertNotNull($report->plan_sla_due_at);
        $this->assertSame(['1'], array_map('strval', $report->pic_checklist['high']));
        $this->assertNotEmpty($report->actual_condition_image_data);

        $this->actingAs($ir)
            ->post(route('safety-reports.ir-decision', $report), [
                'decision' => 'ok',
                'note' => 'Laporan dapat ditindaklanjuti.',
            ])
            ->assertRedirect(route('safety-reports.show', $report));
        $this->assertSame('awaiting_she', $report->fresh()->status);

        $this->actingAs($she)
            ->post(route('safety-reports.she-decision', $report), [
                'decision' => 'ok',
                'note' => 'Laporan disetujui untuk tindakan perbaikan.',
            ])
            ->assertRedirect(route('safety-reports.show', $report));

        $report->refresh();
        $this->assertSame('awaiting_she_plan', $report->status);
        $this->assertNotNull($report->repair_sla_due_at);

        $this->actingAs($she)
            ->post(route('safety-reports.plan', $report), [
                'plan_description' => 'Ganti kabel dan pasang pelindung kabel.',
                'plan_owner' => 'Tim Maintenance',
                'plan_due_date' => now()->addDays(5)->toDateString(),
            ])
            ->assertRedirect(route('safety-reports.show', $report));

        $report->refresh();
        $this->assertSame('repair_open', $report->status);
        $this->assertSame('open', $report->repair_status);
        $this->assertSame('Tim Maintenance', $report->plan_owner);

        $this->actingAs($she)
            ->post(route('safety-reports.close', $report), [
                'repair_completed_on' => now()->toDateString(),
                'proof' => UploadedFile::fake()->image('repair.jpg'),
            ])
            ->assertRedirect(route('safety-reports.show', $report));

        $report->refresh();
        $this->assertSame('closed', $report->status);
        $this->assertSame('close', $report->repair_status);
        $this->assertNotNull($report->closed_at);
        $this->assertNotEmpty($report->repair_proof_data);
        $this->assertDatabaseHas('safety_report_events', [
            'safety_report_id' => $report->id,
            'event_type' => 'repair_closed',
            'to_status' => 'closed',
        ]);
        $this->get(route('safety-reports.show', $report))
            ->assertOk()
            ->assertSee('Timeline SLA')
            ->assertSee('Tepat waktu')
            ->assertSee('Close');

        $this->get(route('safety-reports.print', $report))
            ->assertOk()
            ->assertSee('Foto kondisi awal')
            ->assertSee('Foto kondisi aktual oleh PIC')
            ->assertSee('Foto bukti perbaikan')
            ->assertSee('Riwayat aktivitas')
            ->assertSee('data:image/jpeg;base64,', false);
    }

    public function test_ir_cannot_decide_until_pic_has_verified_the_report(): void
    {
        $report = SafetyReport::factory()->create();
        $ir = User::factory()->create(['role' => 'ir_reviewer']);

        $this->actingAs($ir)
            ->post(route('safety-reports.ir-decision', $report), [
                'decision' => 'ok',
                'note' => 'Belum waktunya.',
            ])
            ->assertStatus(409);
    }

    public function test_she_rejection_returns_to_pic_and_pic_resends_to_she(): void
    {
        $report = SafetyReport::factory()->create([
            'risk_level' => 'medium',
            'status' => 'awaiting_she',
            'verified_at' => now(),
            'verification_due_at' => now()->addWeekdays(3),
        ]);
        $pic = User::factory()->create(['role' => 'safety_pic']);
        $she = User::factory()->create(['role' => 'she']);

        $this->actingAs($she)
            ->post(route('safety-reports.she-decision', $report), [
                'decision' => 'ng',
                'note' => 'Perlu melengkapi informasi lokasi.',
            ])
            ->assertRedirect(route('safety-reports.show', $report));

        $report->refresh();
        $this->assertSame('returned_to_pic', $report->status);
        $this->assertSame('she', $report->return_to_stage);

        $this->actingAs($pic)
            ->post(route('safety-reports.verify', $report), [
                'checklist' => ['medium' => [1]],
                'note' => 'Informasi sudah dilengkapi.',
                'actual_image' => UploadedFile::fake()->image('actual.jpg'),
            ])
            ->assertRedirect(route('safety-reports.show', $report));

        $report->refresh();
        $this->assertSame('awaiting_she', $report->status);
        $this->assertNull($report->return_to_stage);
        $this->assertSame(2, $report->events()->count());
    }

    public function test_pic_risk_level_is_derived_from_checklist_priority(): void
    {
        Storage::fake('local');
        $pic = User::factory()->create(['role' => 'safety_pic']);

        foreach ([
            [['high' => [2], 'medium' => [1], 'low' => [3]], 'high'],
            [['medium' => [1], 'low' => [2]], 'medium'],
            [['low' => [1]], 'low'],
        ] as [$checklist, $expectedRisk]) {
            $report = SafetyReport::factory()->create();

            $this->actingAs($pic)
                ->post(route('safety-reports.verify', $report), [
                    'checklist' => $checklist,
                    'actual_image' => UploadedFile::fake()->image('actual.jpg'),
                ])
                ->assertRedirect(route('safety-reports.show', $report));

            $report->refresh();
            $this->assertSame($expectedRisk, $report->risk_level);
            $this->assertSame('awaiting_ir', $report->status);
            $this->assertNotEmpty($report->actual_condition_image_data);
        }
    }

    public function test_pic_cannot_verify_without_a_checklist_selection_or_actual_condition_photo(): void
    {
        $report = SafetyReport::factory()->create();
        $pic = User::factory()->create(['role' => 'safety_pic']);

        $this->actingAs($pic)
            ->from(route('safety-reports.show', $report))
            ->post(route('safety-reports.verify', $report), [
                'checklist' => ['high' => [], 'medium' => [], 'low' => []],
            ])
            ->assertRedirect(route('safety-reports.show', $report))
            ->assertSessionHasErrors(['checklist', 'actual_image']);

        $this->assertSame('awaiting_pic', $report->fresh()->status);
    }

    public function test_safety_dashboard_shows_summary_counts(): void
    {
        SafetyReport::factory()->create(['status' => 'awaiting_pic']);
        SafetyReport::factory()->create(['status' => 'awaiting_ir', 'risk_level' => 'high']);
        SafetyReport::factory()->create(['status' => 'repair_open', 'risk_level' => 'medium']);
        SafetyReport::factory()->create(['status' => 'closed', 'risk_level' => 'low']);

        $this->actingAs(User::factory()->create(['role' => 'safety_pic']))
            ->get(route('safety-reports.index'))
            ->assertOk()
            ->assertSee('Total laporan')
            ->assertSee('Antrean PIC')
            ->assertSee('Menunggu IR')
            ->assertSee('Menunggu SHE')
            ->assertSee('Risiko aktif:')
            ->assertSee('Perbaikan aktif');
    }

    public function test_print_report_is_only_available_after_closure(): void
    {
        $report = SafetyReport::factory()->create(['status' => 'repair_open']);
        $pic = User::factory()->create(['role' => 'safety_pic']);

        $this->actingAs($pic)
            ->get(route('safety-reports.print', $report))
            ->assertNotFound();
    }

    public function test_closed_reports_show_print_button_and_open_reports_do_not(): void
    {
        $closedReport = SafetyReport::factory()->create(['status' => 'closed']);
        $openReport = SafetyReport::factory()->create(['status' => 'repair_open']);

        $response = $this->actingAs(User::factory()->create(['role' => 'she']))
            ->get(route('safety-reports.index'))
            ->assertOk()
            ->assertSee('Cetak / Simpan PDF')
            ->assertSee(route('safety-reports.print', $closedReport));

        $response->assertDontSee(route('safety-reports.print', $openReport));
    }

    public function test_report_images_are_served_from_the_database(): void
    {
        $this->verifyEmployeeIdentity();

        $this->post(route('safety-reports.store'), [
            'section' => 'Assembly',
            'location' => 'Line 1',
            'hazard_description' => 'Kabel terkelupas di jalur pejalan kaki.',
            'reported_to_supervisor' => 'sudah',
            'ky_created' => 'sudah',
            'image' => UploadedFile::fake()->image('hazard.png'),
        ])->assertRedirect(route('safety-reports.confirmation'));

        $report = SafetyReport::query()->firstOrFail();

        $this->actingAs(User::factory()->create(['role' => 'safety_pic']))
            ->get(route('safety-reports.image', [$report, 'original']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_admin_can_manage_employee_records_in_the_application_database(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('safety-report-employees.index'))
            ->assertOk()
            ->assertSee('class="main-content"', false)
            ->assertSee('Data Karyawan Safety')
            ->assertSee('Tambah Karyawan')
            ->assertSee('NPK')
            ->assertSee('Belum ada data karyawan');

        $this->post(route('safety-report-employees.store'), [
            'nrp' => '009876',
            'name' => 'Siti Aminah',
            'is_active' => '1',
        ])->assertRedirect(route('safety-report-employees.index'));

        $employee = SafetyReportEmployee::query()->where('nrp', '009876')->firstOrFail();
        $this->assertTrue($employee->is_active);

        $this->put(route('safety-report-employees.update', $employee), [
            'nrp' => '009876',
            'name' => 'Siti A.',
            'is_active' => '0',
        ])->assertRedirect(route('safety-report-employees.index'));

        $this->assertSame('Siti A.', $employee->fresh()->name);
        $this->assertFalse($employee->fresh()->is_active);
    }

    public function test_admin_can_create_and_update_users_with_management_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post(route('users.store'), [
            'name' => 'Management User',
            'email' => 'management@example.com',
            'password' => 'password123',
            'role' => 'management',
        ])->assertRedirect(route('users.index'))
            ->assertSessionHasNoErrors();

        $managementUser = User::query()->where('email', 'management@example.com')->firstOrFail();
        $this->assertSame('management', $managementUser->role);

        $this->put(route('users.update', $managementUser), [
            'name' => 'Updated Management User',
            'email' => $managementUser->email,
            'password' => '',
            'role' => 'management',
        ])->assertRedirect(route('users.index'))
            ->assertSessionHasNoErrors();

        $this->assertSame('management', $managementUser->fresh()->role);
        $this->assertSame('Updated Management User', $managementUser->fresh()->name);
    }

    public function test_admin_can_import_and_update_employees_from_excel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        SafetyReportEmployee::query()->create([
            'nrp' => '000123',
            'name' => 'Nama Lama',
            'is_active' => false,
        ]);

        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->fromArray([
            ['NPK', 'Nama'],
            ['000123', 'Nama Diperbarui'],
            ['000456', 'Karyawan Baru'],
        ]);

        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'safety-employees-'.uniqid('', true).'.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $file = new UploadedFile(
                $path,
                'employees.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true,
            );

            $this->actingAs($admin)
                ->post(route('safety-report-employees.import'), ['file' => $file])
                ->assertRedirect(route('safety-report-employees.index'))
                ->assertSessionHas('success', '2 data karyawan berhasil diimpor atau diperbarui.');
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }

        $this->assertDatabaseCount('safety_report_employees', 2);
        $this->assertDatabaseHas('safety_report_employees', [
            'nrp' => '000123',
            'name' => 'Nama Diperbarui',
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('safety_report_employees', [
            'nrp' => '000456',
            'name' => 'Karyawan Baru',
            'is_active' => true,
        ]);
    }

    public function test_employee_excel_import_rejects_duplicate_or_invalid_rows_without_partial_import(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->fromArray([
            ['NPK', 'Nama'],
            ['000123', 'Karyawan Satu'],
            ['000123', 'Karyawan Duplikat'],
        ]);

        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'safety-employees-'.uniqid('', true).'.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $file = new UploadedFile(
                $path,
                'employees.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true,
            );

            $this->actingAs($admin)
                ->from(route('safety-report-employees.index'))
                ->post(route('safety-report-employees.import'), ['file' => $file])
                ->assertRedirect(route('safety-report-employees.index'))
                ->assertSessionHasErrors('file');
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }

        $this->assertDatabaseCount('safety_report_employees', 0);
    }

    public function test_safety_report_list_shows_report_number_and_hazard_description(): void
    {
        $report = SafetyReport::factory()->create([
            'report_number' => 'SH-2026-009999',
            'hazard_description' => 'Lantai licin di jalur operator.',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'safety_pic']))
            ->get(route('safety-reports.index'))
            ->assertOk()
            ->assertSee('SH-2026-009999')
            ->assertSee('Keterangan')
            ->assertSee('Lantai licin di jalur operator.');
    }

    public function test_failed_login_displays_a_clear_error_and_marks_the_fields_invalid(): void
    {
        $this->post(route('login'), [
            'email' => 'unknown@example.com',
            'password' => 'wrong-password',
        ])->assertRedirect();

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('class="dashboard-login-page"', false)
            ->assertSee('img/hero-bg.jpg')
            ->assertSee('Login gagal')
            ->assertSee('These credentials do not match our records.')
            ->assertSee('class="form-control is-invalid"', false)
            ->assertSee('Kata Sandi');
    }

    public function test_ir_and_she_only_have_dashboard_and_safety_report_access(): void
    {
        foreach (['ir_reviewer', 'she'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->actingAs($user);

            $this->view('components.sidebar', ['isAdmin' => false])
                ->assertSee('Dashboard')
                ->assertSee('Safety & Healthy')
                ->assertDontSee('Feedback')
                ->assertDontSee('Organisasi');

            foreach (['home', 'dashboard'] as $routeName) {
                $route = app('router')->getRoutes()->getByName($routeName);
                $this->assertContains('dashboard.safety', $route->gatherMiddleware());

                $request = Request::create('/'.$route->uri());
                $request->setRouteResolver(fn () => $route);
                $request->setUserResolver(fn () => $user);

                $response = app(EnsureDashboardSafetyAccess::class)->handle($request, fn () => response('allowed'));
                $this->assertSame(200, $response->getStatusCode());
            }

            $this->get(route('safety-reports.index'))->assertOk();
            $this->get(route('feedbacks.index'))->assertForbidden();
            $this->get(route('users.index'))->assertForbidden();
        }
    }

    public function test_management_can_open_safety_and_manage_own_profile_without_role_access(): void
    {
        $managementUser = User::factory()->create(['role' => 'management']);
        $this->actingAs($managementUser);

        $this->view('components.sidebar', ['isAdmin' => false])
            ->assertSee('Dashboard')
            ->assertSee('Safety')
            ->assertDontSee('Feedback');

        $this->get(route('safety-reports.index'))->assertOk();
        $this->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya')
            ->assertDontSee('name="role"', false);
        $this->get(route('users.index'))->assertForbidden();

        $this->put(route('profile.update'), [
            'name' => 'Management Updated',
            'email' => $managementUser->email,
            'role' => 'admin',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertSame('management', $managementUser->fresh()->role);
        $this->assertSame('Management Updated', $managementUser->fresh()->name);
    }

    public function test_user_can_change_own_password_after_confirming_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'password' => 'old-password',
        ]);
        $this->actingAs($user);

        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));

        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'incorrect-password',
            'password' => 'another-password',
            'password_confirmation' => 'another-password',
        ])->assertRedirect()
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
    }

    private function verifyEmployeeIdentity(): void
    {
        $this->registerEmployee();

        $this->post(route('safety-reports.verify-employee'), [
            'npk' => '12345',
            'name' => 'Budi Santoso',
        ])->assertRedirect(route('safety-reports.create'));
    }

    private function registerEmployee(): void
    {
        DB::table('safety_report_employees')->updateOrInsert(
            ['nrp' => '12345'],
            ['name' => 'Budi Santoso', 'is_active' => true],
        );
    }
}
