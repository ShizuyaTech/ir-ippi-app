<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan {{ $safetyReport->report_number }}</title>
    <style>
        @page { size: A4; margin: 16mm; }
        body { color: #172033; font: 12px Arial, sans-serif; line-height: 1.5; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        h2 { border-bottom: 1px solid #cbd5e1; font-size: 15px; margin: 22px 0 10px; padding-bottom: 5px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #cbd5e1; padding: 7px; text-align: left; vertical-align: top; }
        th { background: #eff6ff; width: 30%; }
        img { max-height: 330px; max-width: 100%; object-fit: contain; }
        .photo { margin: 10px 0 18px; page-break-inside: avoid; }
        .muted { color: #64748b; }
        .print-button { background: #1d4ed8; border: 0; border-radius: 4px; color: white; cursor: pointer; margin-bottom: 16px; padding: 10px 14px; }
        .event { page-break-inside: avoid; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>
    <button class="print-button" type="button" onclick="window.print()">Cetak / Simpan sebagai PDF</button>

    <h1>Laporan Safety &amp; Healthy</h1>
    <p class="muted">{{ $safetyReport->report_number }} · Ditutup {{ $safetyReport->closed_at?->format('d-m-Y H:i') }}</p>

    <h2>Informasi laporan awal</h2>
    <table>
        <tr><th>Nomor laporan</th><td>{{ $safetyReport->report_number }}</td></tr>
        <tr><th>Tanggal laporan</th><td>{{ $safetyReport->created_at->format('d-m-Y H:i') }}</td></tr>
        <tr><th>Pelapor / NPK</th><td>{{ $safetyReport->reporter_name }} / {{ $safetyReport->employee_number }}</td></tr>
        <tr><th>Seksi / lokasi</th><td>{{ $safetyReport->section }} / {{ $safetyReport->location }}</td></tr>
        <tr><th>Sudah melapor ke atasan</th><td>{{ $safetyReport->reported_to_supervisor ? 'Sudah' : 'Belum' }}</td></tr>
        <tr><th>Kiken Yochi (KY)</th><td>{{ $safetyReport->ky_created ? 'Sudah' : 'Belum' }}</td></tr>
        <tr><th>Deskripsi bahaya</th><td>{!! nl2br(e($safetyReport->hazard_description)) !!}</td></tr>
    </table>
    <div class="photo">
        <h2>Foto kondisi awal</h2>
        <img src="{{ $images['initial'] }}" alt="Foto kondisi awal">
    </div>

    <h2>Verifikasi PIC</h2>
    <table>
        <tr><th>Level risiko</th><td>{{ strtoupper($safetyReport->risk_level) }}</td></tr>
        <tr><th>Checklist dipilih</th><td>
            @foreach (\App\Models\SafetyReport::RISK_CHECKLISTS as $level => $items)
                @foreach ($safetyReport->pic_checklist[$level] ?? [] as $number)
                    <div><strong>{{ strtoupper($level) }} {{ $number }}:</strong> {{ $items[$number] ?? '' }}</div>
                @endforeach
            @endforeach
        </td></tr>
        <tr><th>Verifikator PIC</th><td>{{ $safetyReport->verifiedBy?->name ?? '-' }}</td></tr>
        <tr><th>Waktu verifikasi</th><td>{{ $safetyReport->verified_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        <tr><th>Catatan PIC</th><td>{{ $safetyReport->pic_note ?? '-' }}</td></tr>
    </table>
    @if ($images['actual'])
        <div class="photo"><h2>Foto kondisi aktual oleh PIC</h2><img src="{{ $images['actual'] }}" alt="Foto kondisi aktual"></div>
    @endif

    <h2>Review Industrial Relationship</h2>
    <table>
        <tr><th>Keputusan IR</th><td>{{ strtoupper($safetyReport->ir_decision ?? '-') }}</td></tr>
        <tr><th>Reviewer IR</th><td>{{ $safetyReport->irReviewedBy?->name ?? '-' }}</td></tr>
        <tr><th>Waktu review</th><td>{{ $safetyReport->ir_reviewed_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        <tr><th>Catatan IR</th><td>{{ $safetyReport->ir_note ?? '-' }}</td></tr>
    </table>

    <h2>Verifikasi SHE</h2>
    <table>
        <tr><th>Keputusan SHE</th><td>{{ strtoupper($safetyReport->she_decision ?? '-') }}</td></tr>
        <tr><th>Verifikator SHE</th><td>{{ $safetyReport->sheReviewedBy?->name ?? '-' }}</td></tr>
        <tr><th>Waktu verifikasi</th><td>{{ $safetyReport->she_reviewed_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        <tr><th>Catatan SHE</th><td>{{ $safetyReport->she_note ?? '-' }}</td></tr>
    </table>

    <h2>Rencana dan penyelesaian perbaikan</h2>
    <table>
        <tr><th>Rencana perbaikan</th><td>{{ $safetyReport->plan_description ?? '-' }}</td></tr>
        <tr><th>Penanggung jawab</th><td>{{ $safetyReport->plan_owner ?? '-' }}</td></tr>
        <tr><th>Target perbaikan</th><td>{{ $safetyReport->plan_due_date?->format('d-m-Y') ?? '-' }}</td></tr>
        <tr><th>Tenggat SLA perbaikan</th><td>{{ $safetyReport->repair_sla_due_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        <tr><th>Status tindakan</th><td>{{ strtoupper($safetyReport->repair_status ?? '-') }}</td></tr>
        <tr><th>Tanggal perbaikan selesai</th><td>{{ $safetyReport->repair_completed_on?->format('d-m-Y') ?? '-' }}</td></tr>
        <tr><th>Ditutup oleh / waktu</th><td>{{ $safetyReport->closedBy?->name ?? '-' }} / {{ $safetyReport->closed_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
    </table>
    @if ($images['repair'])
        <div class="photo"><h2>Foto bukti perbaikan</h2><img src="{{ $images['repair'] }}" alt="Foto bukti perbaikan"></div>
    @endif

    <h2>Timeline SLA</h2>
    <table>
        <thead><tr><th>Tahap</th><th>Tenggat</th><th>Aktual</th></tr></thead>
        <tbody>
            <tr><td>Verifikasi</td><td>{{ $safetyReport->verification_due_at?->format('d-m-Y H:i') ?? '-' }}</td><td>{{ $safetyReport->verified_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
            <tr><td>Penetapan rencana</td><td>{{ $safetyReport->plan_sla_due_at?->format('d-m-Y H:i') ?? '-' }}</td><td>{{ $safetyReport->plan_created_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
            <tr><td>Penyelesaian perbaikan</td><td>{{ $safetyReport->repair_sla_due_at?->format('d-m-Y H:i') ?? '-' }}</td><td>{{ $safetyReport->repair_completed_on?->format('d-m-Y') ?? '-' }}</td></tr>
            <tr><td>Closure</td><td>{{ $safetyReport->closure_sla_due_at?->format('d-m-Y H:i') ?? '-' }}</td><td>{{ $safetyReport->closed_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        </tbody>
    </table>

    <h2>Riwayat aktivitas</h2>
    <table>
        <thead><tr><th>Waktu</th><th>Aktivitas</th><th>Pelaku</th><th>Status</th><th>Catatan</th></tr></thead>
        <tbody>
            @foreach ($safetyReport->events as $event)
                <tr class="event">
                    <td>{{ $event->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($event->event_type)) }}</td>
                    <td>{{ $event->actor?->name ?? 'Pelapor' }}</td>
                    <td>{{ $event->from_status ? str_replace('_', ' ', $event->from_status).' → ' : '' }}{{ $event->to_status ? str_replace('_', ' ', $event->to_status) : '-' }}</td>
                    <td>{{ $event->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
