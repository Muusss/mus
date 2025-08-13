{{-- resources/views/dashboard/pdf/hasil_akhir.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            margin: 2cm; /* Margin untuk semua sisi */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
        }
        
        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11pt;
            margin: 2px 0;
        }
        
        /* Title Section */
        .title-section {
            text-align: center;
            margin: 20px 0;
        }
        
        .title-section h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        /* Info Box */
        .info-box {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin: 3px 0;
        }
        
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }
        
        .info-value {
            display: table-cell;
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        table thead {
            background-color: #4a5568;
            color: white;
        }
        
        table th {
            padding: 8px;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            border: 1px solid #333;
        }
        
        table td {
            padding: 6px 8px; /* Tambah padding horizontal */
            font-size: 10pt;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        /* Special Rows */
        .ranking-1 {
            background-color: #ffd700 !important;
            font-weight: bold;
        }
        
        .ranking-2 {
            background-color: #c0c0c0 !important;
        }
        
        .ranking-3 {
            background-color: #cd7f32 !important;
        }
        
        /* Summary Box */
        .summary-box {
            margin-top: 30px;
            padding: 15px;
            background-color: #e8f4f8;
            border: 2px solid #2c5282;
            border-radius: 5px;
        }
        
        .summary-box h4 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c5282;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px; /* Tambah padding horizontal */
        }
        
        .signature-box p {
            margin: 5px 0;
        }
        
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>{{ $sekolah->nama }}</h1>
        <p>{{ $sekolah->alamat }}</p>
        <p>Tahun Ajaran {{ $sekolah->tahun_ajaran }} - Semester {{ $sekolah->semester }}</p>
    </div>

    {{-- Title --}}
    <div class="title-section">
        <h3>LAPORAN HASIL PEMILIHAN SISWA TELADAN</h3>
        <p>Metode ROC (Rank Order Centroid) & SMART</p>
    </div>

    {{-- Info Box --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Tanggal Cetak</span>
            <span class="info-value">: {{ $tanggal_cetak }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kelas</span>
            <span class="info-value">: {{ $kelas_filter }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Siswa</span>
            <span class="info-value">: {{ $alternatif->count() }} Siswa</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Kriteria</span>
            <span class="info-value">: {{ $kriteria->count() }} Kriteria</span>
        </div>
    </div>

    {{-- Tabel Kriteria & Bobot --}}
    <h4 class="mt-20 mb-10">1. KRITERIA PENILAIAN DAN BOBOT ROC</h4>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="15%">Kode</th>
                <th width="35%">Kriteria</th>
                <th width="15%">Prioritas</th>
                <th width="15%">Bobot ROC</th>
                <th width="10%">Atribut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriteria as $index => $k)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $k->kode }}</td>
                <td>{{ $k->kriteria }}</td>
                <td class="text-center">{{ $k->urutan_prioritas }}</td>
                <td class="text-center">{{ number_format($k->bobot_roc, 4) }}</td>
                <td class="text-center">{{ ucfirst($k->atribut) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right font-bold">Total Bobot:</td>
                <td class="text-center font-bold">{{ number_format($kriteria->sum('bobot_roc'), 4) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- Tabel Hasil Perankingan --}}
    <h4 class="mt-20 mb-10">2. HASIL PERANKINGAN SISWA TELADAN</h4>
    <table>
        <thead>
            <tr>
                <th width="8%">Rank</th>
                <th width="12%">NIS</th>
                <th width="30%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="8%">JK</th>
                <th width="12%">Nilai Total</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabelPerankingan as $item)
            <tr class="{{ $item->peringkat == 1 ? 'ranking-1' : ($item->peringkat == 2 ? 'ranking-2' : ($item->peringkat == 3 ? 'ranking-3' : '')) }}">
                <td class="text-center font-bold">{{ $item->peringkat }}</td>
                <td class="text-center">{{ $item->nis }}</td>
                <td>{{ $item->nama_siswa }}</td>
                <td class="text-center">{{ $item->kelas }}</td>
                <td class="text-center">{{ $item->jk }}</td>
                <td class="text-center font-bold">{{ number_format($item->nilai, 4) }}</td>
                <td class="text-center">
                    @if($item->peringkat == 1)
                        <strong>SISWA TELADAN</strong>
                    @elseif($item->peringkat <= 3)
                        Nominasi
                    @else
                        Partisipan
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary Box --}}
    @if($tabelPerankingan->first())
    <div class="summary-box">
        <h4>KESIMPULAN</h4>
        <p>Berdasarkan hasil perhitungan menggunakan metode ROC (Rank Order Centroid) untuk pembobotan kriteria 
        dan metode SMART untuk normalisasi nilai, maka siswa yang terpilih sebagai <strong>SISWA TELADAN</strong> adalah:</p>
        
        <div style="margin: 15px 0; padding: 10px; background: white; border-left: 4px solid #2c5282;">
            <table style="border: none; margin: 0;">
                <tr>
                    <td style="border: none; width: 120px;"><strong>Nama</strong></td>
                    <td style="border: none;">: {{ $tabelPerankingan->first()->nama_siswa }}</td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>NIS</strong></td>
                    <td style="border: none;">: {{ $tabelPerankingan->first()->nis }}</td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Kelas</strong></td>
                    <td style="border: none;">: {{ $tabelPerankingan->first()->kelas }}</td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Nilai Total</strong></td>
                    <td style="border: none;">: {{ number_format($tabelPerankingan->first()->nilai, 4) }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Detail Penilaian --}}
    <h4 class="mt-20 mb-10">3. DETAIL PENILAIAN SISWA</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="8%">No</th>
                <th rowspan="2" width="20%">Nama Siswa</th>
                <th colspan="{{ $kriteria->count() }}" class="text-center">Kriteria Penilaian</th>
            </tr>
            <tr>
                @foreach($kriteria as $k)
                <th width="{{ 72 / $kriteria->count() }}%">{{ $k->kode }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($alternatif as $index => $alt)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $alt->nama_siswa }}</td>
                @foreach($kriteria as $k)
                    @php
                        $nilai = $tabelPenilaian->where('alternatif.id', $alt->id)
                                              ->where('kriteria.id', $k->id)
                                              ->first();
                    @endphp
                    <td class="text-center" style="font-size: 9pt;">
                        {{ $nilai ? $nilai->sub_kriteria : '-' }}
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer / Tanda Tangan --}}
    <div class="footer">
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p>Kepala Sekolah</p>
                <div class="signature-line"></div>
                <p class="font-bold">(.................................)</p>
                <p>NIP. </p>
            </div>
            <div class="signature-box">
                <p>&nbsp;</p>
                <p>Wali Kelas</p>
                <div class="signature-line"></div>
                <p class="font-bold">{{ $user->name ?? '(.................................)' }}</p>
                <p>NIP. </p>
            </div>
            <div class="signature-box">
                <p>Cirebon, {{ $tanggal_cetak }}</p>
                <p>Petugas Admin</p>
                <div class="signature-line"></div>
                <p class="font-bold">(.................................)</p>
                <p>NIP. </p>
            </div>
        </div>
    </div>
</body>
</html>