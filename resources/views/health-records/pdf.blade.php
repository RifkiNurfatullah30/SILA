<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekam Kesehatan Lansia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #222;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #198754;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            color: #198754;
            margin: 0 0 5px;
        }
        .header h2 {
            font-size: 14px;
            color: #333;
            margin: 0 0 3px;
            font-weight: normal;
        }
        .header p {
            font-size: 11px;
            color: #555;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #198754;
            color: #fff;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #198754;
        }
        td {
            padding: 5px 4px;
            border: 1px solid #ccc;
            font-size: 9px;
            color: #222;
            background-color: #fff;
        }
        tr:nth-child(even) td {
            background-color: #f0f0f0;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SILA - Sistem Informasi Lansia Aktif</h1>
        <h2>Rekam Kesehatan Lansia</h2>
        <p>Lingkup: {{ $filterLabel }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama</th>
                <th>Kampung</th>
                <th>RW</th>
                <th>Tanggal</th>
                <th>TD (mmHg)</th>
                <th>Gula Darah</th>
                <th>BB/TB</th>
                <th>BMI</th>
                <th>Kolesterol</th>
                <th>Asam Urat</th>
                <th>Keluhan</th>
                <th>Diagnosa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $record)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $record->lansia->nama }}</td>
                    <td>{{ $record->lansia->kampung ?? '-' }}</td>
                    <td>{{ $record->lansia->rw }}</td>
                    <td>{{ $record->tanggal_pemeriksaan->format('d/m/Y') }}</td>
                    <td>{{ $record->tekanan_darah ?? '-' }}</td>
                    <td>{{ $record->gula_darah ? $record->gula_darah . ' mg/dL' : '-' }}</td>
                    <td>{{ $record->berat_badan ?? '-' }}/{{ $record->tinggi_badan ?? '-' }}</td>
                    <td>{{ $record->bmi ?? '-' }}</td>
                    <td>{{ $record->kolesterol ? $record->kolesterol . ' mg/dL' : '-' }}</td>
                    <td>{{ $record->asam_urat ? $record->asam_urat . ' mg/dL' : '-' }}</td>
                    <td>{{ Str::limit($record->keluhan, 30) ?? '-' }}</td>
                    <td>{{ Str::limit($record->diagnosa, 30) ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>
</html>
