<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keaktifan Lansia - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
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
            color: #555;
            margin: 0 0 3px;
            font-weight: normal;
        }
        .header p {
            font-size: 11px;
            color: #888;
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
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
        }
        .badge-success { background-color: #198754; }
        .badge-primary { background-color: #0d6efd; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-danger { background-color: #dc3545; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #888;
        }
        .summary {
            margin-top: 15px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SILA - Sistem Informasi Lansia Aktif</h1>
        <h2>Laporan Keaktifan Lansia</h2>
        <p>Periode: {{ $namaBulan }} {{ $tahun }} | Total Kegiatan: {{ $totalKegiatanBulan }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama</th>
                <th>RW</th>
                <th>JK</th>
                <th class="text-center">Kegiatan</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Persentase</th>
                <th class="text-center">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lansias as $i => $lansia)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $lansia->nama }}</td>
                    <td>{{ $lansia->rw }}</td>
                    <td>{{ $lansia->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}</td>
                    <td class="text-center">{{ $lansia->total_kegiatan_valid_bulan }}</td>
                    <td class="text-center">{{ $lansia->total_hadir_bulan }}</td>
                    <td class="text-center">{{ $lansia->persentase_bulan }}%</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $lansia->badge_bulan }}">{{ $lansia->kategori_bulan }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <strong>Keterangan Kategori:</strong><br>
        <span class="badge badge-success">Sangat Aktif</span> 80% - 100% |
        <span class="badge badge-primary">Aktif</span> 60% - 79% |
        <span class="badge badge-warning">Cukup Aktif</span> 40% - 59% |
        <span class="badge badge-danger">Kurang Aktif</span> &lt; 40%
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>
</html>
