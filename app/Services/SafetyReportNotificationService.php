<?php
namespace App\Services;

use App\Models\SafetyReport;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SafetyReportNotificationService
{
    /**
     * Kirim notifikasi kepada semua pengguna dengan role yang ditentukan.
     *
     * @param array<int, string> $roles
     */
    public function notify(
        SafetyReport $report,
        array $roles,
        string $subject,
        string $message
    ): void {
        try {
            $recipients = User::query()
                ->whereIn('role', $roles)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->distinct()
                ->pluck('email');

            if ($recipients->isEmpty()) {
                Log::warning(
                    'Notifikasi laporan tidak dikirim: penerima tidak ditemukan.',
                    [
                        'report_id' => $report->id,
                        'report_number' => $report->report_number,
                        'roles' => $roles,
                    ]
                );

                return;
            }

            $url = route('safety-reports.show', $report);

            foreach ($recipients as $recipient) {
                Mail::raw(
                    $message
                    . "\n\nNomor laporan: " . $report->report_number
                    . "\nStatus: " . $report->status
                    . "\nLokasi: " . $report->location
                    . "\n\nBuka laporan: " . $url,
                    function ($mail) use ($recipient, $subject): void {
                        $mail->to($recipient)->subject($subject);
                    }
                );
            }
        } catch (Throwable $exception) {
            Log::error(
                'Pengiriman notifikasi safety report gagal.',
                [
                    'report_id' => $report->id,
                    'report_number' => $report->report_number,
                    'roles' => $roles,
                    'error' => $exception->getMessage(),
                ]
            );
        }
    }
}
