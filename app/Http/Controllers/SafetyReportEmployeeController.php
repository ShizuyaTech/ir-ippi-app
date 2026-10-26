<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportSafetyReportEmployeesRequest;
use App\Http\Requests\StoreSafetyReportEmployeeRequest;
use App\Http\Requests\UpdateSafetyReportEmployeeRequest;
use App\Models\SafetyReportEmployee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as SpreadsheetReaderException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SafetyReportEmployeeController extends Controller
{
    public function index(): View
    {
        $employees = SafetyReportEmployee::query()
            ->when(request('search'), function ($query, string $search): void {
                $search = '%'.trim($search).'%';
                $query->where(function ($query) use ($search): void {
                    $query->where('nrp', 'like', $search)
                        ->orWhere('name', 'like', $search);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('safety-report-employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('safety-report-employees.form', [
            'employee' => new SafetyReportEmployee(['is_active' => true]),
        ]);
    }

    public function store(StoreSafetyReportEmployeeRequest $request): RedirectResponse
    {
        SafetyReportEmployee::query()->create($request->validated());

        return redirect()
            ->route('safety-report-employees.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit(SafetyReportEmployee $safetyReportEmployee): View
    {
        return view('safety-report-employees.form', [
            'employee' => $safetyReportEmployee,
        ]);
    }

    public function update(
        UpdateSafetyReportEmployeeRequest $request,
        SafetyReportEmployee $safetyReportEmployee,
    ): RedirectResponse {
        $safetyReportEmployee->update($request->validated());

        return redirect()
            ->route('safety-report-employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(SafetyReportEmployee $safetyReportEmployee): RedirectResponse
    {
        $safetyReportEmployee->delete();

        return redirect()
            ->route('safety-report-employees.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Karyawan');
        $sheet->fromArray(['NPK', 'Nama'], null, 'A1');
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(35);

        return response()->streamDownload(
            static function () use ($spreadsheet): void {
                (new Xlsx($spreadsheet))->save('php://output');
                $spreadsheet->disconnectWorksheets();
            },
            'template-data-karyawan-safety.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        );
    }

    public function import(ImportSafetyReportEmployeesRequest $request): RedirectResponse
    {
        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        } catch (SpreadsheetReaderException) {
            throw ValidationException::withMessages([
                'file' => 'File Excel tidak dapat dibaca. Pastikan file tidak rusak dan gunakan template yang disediakan.',
            ]);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $headers = [
            mb_strtolower(trim((string) $sheet->getCell('A1')->getFormattedValue())),
            mb_strtolower(trim((string) $sheet->getCell('B1')->getFormattedValue())),
        ];

        if ($headers !== ['npk', 'nama']) {
            $spreadsheet->disconnectWorksheets();

            throw ValidationException::withMessages([
                'file' => 'Header file harus berurutan: kolom A "NPK" dan kolom B "Nama".',
            ]);
        }

        if ($highestRow > 5001) {
            $spreadsheet->disconnectWorksheets();

            throw ValidationException::withMessages([
                'file' => 'File berisi lebih dari 5.000 baris data. Pecah file menjadi beberapa bagian.',
            ]);
        }

        $employees = [];
        $errors = [];

        for ($rowNumber = 2; $rowNumber <= $highestRow; $rowNumber++) {
            $npkCell = $sheet->getCell("A{$rowNumber}");
            $nameCell = $sheet->getCell("B{$rowNumber}");

            if ($npkCell->isFormula() || $nameCell->isFormula()) {
                $errors[] = "Baris {$rowNumber}: formula Excel tidak diizinkan. Masukkan NPK dan nama sebagai nilai biasa.";

                continue;
            }

            $npk = trim((string) $npkCell->getFormattedValue());
            $name = trim((string) $nameCell->getFormattedValue());

            if ($npk === '' && $name === '') {
                continue;
            }

            if ($npk === '' || mb_strlen($npk) > 50) {
                $errors[] = "Baris {$rowNumber}: NPK wajib diisi dan maksimal 50 karakter.";
            }

            if ($name === '' || mb_strlen($name) > 255) {
                $errors[] = "Baris {$rowNumber}: nama wajib diisi dan maksimal 255 karakter.";
            }

            if ($npk !== '' && isset($employees[$npk])) {
                $errors[] = "Baris {$rowNumber}: NPK {$npk} tercantum lebih dari satu kali.";
            }

            if ($npk !== '' && $name !== '') {
                $employees[$npk] = [
                    'nrp' => $npk,
                    'name' => $name,
                    'is_active' => true,
                ];
            }
        }

        $spreadsheet->disconnectWorksheets();

        if ($errors !== []) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        if ($employees === []) {
            throw ValidationException::withMessages([
                'file' => 'File Excel tidak berisi data karyawan.',
            ]);
        }

        $timestamp = now();
        $records = array_map(
            static fn (array $employee): array => $employee + [
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            array_values($employees),
        );

        DB::transaction(function () use ($records): void {
            SafetyReportEmployee::query()->upsert($records, ['nrp'], ['name', 'updated_at']);
        });

        return redirect()
            ->route('safety-report-employees.index')
            ->with('success', count($records).' data karyawan berhasil diimpor atau diperbarui.');
    }
}
