<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .late {
            color: red;
            font-weight: bold;
        }
        .on-time {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN ABSENSI PEGAWAI</h2>
        <p>Periode: {{ date('F Y', strtotime(request('year').'-'.request('month').'-01')) }}</p>
        @if(isset($cabangId) && $cabangId)
            <p>Lokasi: {{ \App\Models\Cabang::find($cabangId)->nama_cabang }}</p>
        @else
            <p>Lokasi: Semua Lokasi</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Lokasi</th>
                <th>Kategori/Golongan</th>
                <th>Morning Check-in</th>
                <th>Evening Check-in</th>
                <th>Late Penalty</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->nama_pegawai ?? "Tidak Ada Data"}}</td>
                    <td>{{ $item->user->cabang->nama_cabang ?? "Tidak Ada Data"}}</td>
                    <td>{{ $item->user->jabatan->nama_jabatan ?? "Tidak Ada Data"}}</td>
                    <td>{{ $item->jam_masuk ?? "Tidak Ada Data"}}</td>
                    <td>{{ $item->jam_masuk_sore ?? "Tidak Ada Data"}}</td>
                    <td>
                        @php
                            $lateMinutes = 0;
                            if ($item->jam_masuk) {
                                $startTime = \Carbon\Carbon::createFromFormat('H:i', '07:30');
                                $checkInTime = \Carbon\Carbon::parse($item->jam_masuk);

                                if ($checkInTime->gt($startTime)) {
                                    $lateMinutes = $checkInTime->diffInMinutes($startTime);
                                }
                            }
                        @endphp

                        @if($lateMinutes > 0)
                            <span class="late">{{ $lateMinutes }} menit</span>
                        @else
                            <span class="on-time">Tepat Waktu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Data absensi tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
