<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai - {{ $siswa->nama_siswa }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px double #333;
            margin-bottom: 30px;
        }
        
        .header img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18pt;
            margin-bottom: 5px;
            color: #ff6b35;
        }
        
        .header h2 {
            font-size: 16pt;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10pt;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 5px;
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #ff6b35;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #ff6b35;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th {
            background: #ff6b35;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11pt;
        }
        
        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10pt;
            font-weight: bold;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .score-box {
            background: linear-gradient(135deg, #ff6b35, #ff8c5a);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        
        .score-box h3 {
            font-size: 16pt;
            margin-bottom: 10px;
        }
        
        .score-value {
            font-size: 36pt;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .rank-info {
            font-size: 14pt;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            width: 150px;
            margin: 60px auto 5px;
        }
        
        .notes {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        
        .notes h4 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .notes p {
            color: #856404;
            font-size: 10pt;
            margin: 5px 0;
        }
        
        @page {
            margin: 20mm;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN HASIL PENILAIAN SISWA TELADAN</h1>
        <h2>{{ $sekolah->nama }}</h2>
        <p>{{ $sekolah->alamat }}</p>
        <p>Tahun Ajaran {{ $sekolah->tahun_ajaran }} - Semester {{ $sekolah->semester }}</p>
    </div>
    
    <!-- Info Siswa -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Siswa</div>
                <div class="info-value">: <strong>{{ $siswa->nama_siswa }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">NISN</div>
                <div class="info-value">: {{ $siswa->nis }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kelas</div>
                <div class="info-value">: {{ $siswa->kelas }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Kelamin</div>
                <div class="info-value">: {{ $siswa->jk == 'Lk' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Periode Penilaian</div>
                <div class="info-value">: {{ $periode->nama_periode }}</div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Nilai per Kriteria -->
    <h3 class="section-title">Detail Penilaian per Kriteria</h3>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">Kode</th>
                <th width="30%">Kriteria</th>
                <th width="20%">Sub Kriteria</th>
                <th width="15%" class="text-center">Nilai Asli</th>
                <th width="15%" class="text-center">Nilai Normal</th>
                <th width="10%" class="text-center">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriteria as $krit)
                @php
                    $nilai = $penilaian[$krit->id] ?? null;
                @endphp
                <tr>
                    <td class="text-center"><strong>{{ $krit->kode }}</strong></td>
                    <td>{{ $krit->kriteria }}</td>
                    <td>
                        @if($nilai && $nilai->subKriteria)
                            {{ $nilai->subKriteria->label }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($nilai)
                            <span class="badge {{ $nilai->nilai_asli >= 3 ? 'badge-success' : ($nilai->nilai_asli >= 2 ? 'badge-warning' : 'badge-info') }}">
                                {{ $nilai->nilai_asli }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $nilai ? number_format($nilai->nilai_normal, 4) : '-' }}
                    </td>
                    <td class="text-center">
                        {{ number_format($krit->bobot_roc * 100, 1) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Score Box -->
    @if($nilaiAkhir)
        <div class="score-box">
            <h3>SKOR PREFERENSI AKHIR</h3>
            <div class="score-value">{{ number_format($nilaiAkhir->total, 4) }}</div>
            <div class="rank-info">
                @if($ranking)
                    Peringkat {{ $ranking }} di Kelas {{ $siswa->kelas }}
                @endif
            </div>
        </div>
    @endif
    
    <!-- Keterangan -->
    <div class="notes">
        <h4>Keterangan Penilaian:</h4>
        <p><strong>Nilai Asli:</strong> Nilai mentah dengan skala 1-4 sesuai rubrik masing-masing kriteria</p>
        <p><strong>Nilai Normal:</strong> Nilai yang sudah dinormalisasi ke skala 0-1 menggunakan metode SMART</p>
        <p><strong>Bobot:</strong> Tingkat kepentingan kriteria yang dihitung menggunakan metode ROC</p>
        <p><strong>Skor Preferensi:</strong> Total nilai akhir dari perkalian nilai normal dengan bobot</p>
    </div>
    
    <!-- Interpretasi Hasil -->
    <h3 class="section-title">Interpretasi Hasil</h3>
    <table class="table">
        <tr>
            <td width="30%"><strong>Status</strong></td>
            <td>
                @if($ranking == 1)
                    <span class="badge badge-success">SISWA TELADAN KELAS {{ $siswa->kelas }}</span>
                @elseif($ranking <= 3)
                    <span class="badge badge-warning">NOMINASI SISWA TELADAN</span>
                @elseif($ranking <= 10)
                    <span class="badge badge-info">10 BESAR KELAS</span>
                @else
                    <span class="badge">PARTISIPAN</span>
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Kategori Prestasi</strong></td>
            <td>
                @if($nilaiAkhir && $nilaiAkhir->total >= 0.8)
                    Sangat Baik
                @elseif($nilaiAkhir && $nilaiAkhir->total >= 0.6)
                    Baik
                @elseif($nilaiAkhir && $nilaiAkhir->total >= 0.4)
                    Cukup
                @else
                    Perlu Peningkatan
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Rekomendasi</strong></td>
            <td>
                @if($ranking <= 3)
                    Pertahankan prestasi dan menjadi teladan bagi siswa lain
                @elseif($ranking <= 10)
                    Tingkatkan lagi prestasi untuk mencapai peringkat lebih baik
                @else
                    Perlu peningkatan di beberapa aspek penilaian
                @endif
            </td>
        </tr>
    </table>
    
    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <div class="signature-line"></div>
            <p><strong>(...........................)</strong></p>
            <p>NIP. ...........................</p>
        </div>
        <div class="signature-box">
            <p>Cirebon, {{ now()->format('d F Y') }}</p>
            <p>Wali Kelas {{ $siswa->kelas }}</p>
            <div class="signature-line"></div>
            <p><strong>(...........................)</strong></p>
            <p>NIP. ...........................</p>
        </div>
        <div class="signature-box">
            <p>&nbsp;</p>
            <p>Orang Tua/Wali</p>
            <div class="signature-line"></div>
            <p><strong>(...........................)</strong></p>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p class="text-center" style="font-size: 10pt; color: #666;">
            Dokumen ini dicetak pada {{ now()->format('d F Y H:i:s') }}<br>
            Sistem Pendukung Keputusan Siswa Teladan - SDIT As Sunnah Cirebon
        </p>
    </div>
</body>
</html>