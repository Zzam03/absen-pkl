
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi PKL</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            color: #1d3557;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 7px 5px;
            text-align: left;
        }

        th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f5f7fa;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>

    <h2>LAPORAN REKAP ABSENSI PKL</h2>
    <div class="subtitle">
        Sistem Informasi Monitoring Praktik Kerja Lapangan
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Sekolah</th>
                <th>Lokasi PKL</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Status</th>
                <th>Di Luar Area</th>
                <th>Jarak (m)</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rows as $index => $attendance)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">
                        {{ $attendance->date?->format('d-m-Y') ?? '-' }}
                    </td>
                    <td>{{ $attendance->student->name ?? '-' }}</td>
                    <td>{{ $attendance->student->school_name ?? '-' }}</td>
                    <td>{{ $attendance->placement->location->name ?? '-' }}</td>
                    <td class="center">
                        {{ $attendance->check_in_time ? substr($attendance->check_in_time, 0, 5) : '-' }}
                    </td>
                    <td class="center">
                        {{ $attendance->check_out_time ? substr($attendance->check_out_time, 0, 5) : '-' }}
                    </td>
                    <td class="center">{{ ucfirst($attendance->status) }}</td>
                    <td class="center">
                        {{ $attendance->is_outside_area ? 'Ya' : 'Tidak' }}
                    </td>
                    <td class="center">
                        {{ $attendance->check_in_distance ?? '-' }}
                    </td>
                    <td>{{ $attendance->note ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="center">
                        Belum ada data absensi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
        <br>
        Total data: {{ $rows->count() }} absensi
    </div>

</body>
</html>
