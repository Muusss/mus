<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai Siswa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f4f4f4;
        }
        
        .email-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #ff6b35, #ff8c5a);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .score-card {
            background: linear-gradient(135deg, #ff6b35, #ff8c5a);
            color: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
        }
        
        .score-title {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .score-value {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .rank-info {
            font-size: 16px;
            opacity: 0.95;
        }
        
        .table-container {
            overflow-x: auto;
            margin: 25px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #ff6b35;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
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
        
        .cta-section {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            margin-top: 30px;
            border-radius: 8px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #ff6b35;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 10px 5px;
        }
        
        .btn:hover {
            background: #e55100;
        }
        
        .footer {
            background: #333;
            color: white;
            padding: 25px;
            text-align: center;
            font-size: 14px;
        }
        
        .footer a {
            color: #ff8c5a;
            text-decoration: none;
        }
        
        .social-links {
            margin-top: 15px;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: white;
            text-decoration: none;
        }
        
        @media only screen and (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN NILAI SISWA TELADAN</h1>
            <p>SDIT As Sunnah Cirebon</p>
            <p>{{ $periode->nama_periode }}</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
                <p>Bapak/Ibu Wali Murid yang terhormat,</p>
                <p>Berikut kami sampaikan laporan hasil penilaian siswa teladan untuk:</p>
            </div>
            
            <!-- Student Info -->
            <div class="info-box">
                <div class="info-grid">
                    <div class="info-label">Nama Siswa</div>
                    <div class="info-value"><strong>{{ $siswa->nama_siswa }}</strong></div>
                    
                    <div class="info-label">NISN</div>
                    <div class="info-value">{{ $siswa->nis }}</div>
                    
                    <div class="info-label">Kelas</div>
                    <div class="info-value">{{ $siswa->kelas }}</div>
                    
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $siswa->jk == 'Lk' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
            </div>
            
            <!-- Score Card -->
            @if($nilaiAkhir)
            <div class="score-card">
                <div class="score-title">SKOR PREFERENSI AKHIR</div>
                <div class="score-value">{{ number_format($nilaiAkhir->total * 100, 2) }}%</div>
                @if($ranking)
                <div class="rank-info">
                    Peringkat {{ $ranking }} di Kelas {{ $siswa->kelas }}
                </div>
                @endif
            </div>
            @endif
            
            <!-- Detail Table -->
            <h3 style="color: #ff6b35; margin-top: 30px;">Detail Penilaian per Kriteria</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th style="text-align: center;">Nilai</th>
                            <th style="text-align: center;">Bobot</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $krit)
                            @php
                                $nilai = $penilaian[$krit->id] ?? null;
                                $nilaiAsli = $nilai ? $nilai->nilai_asli : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $krit->kode }}</strong> - {{ $krit->kriteria }}
                                </td>
                                <td style="text-align: center;">
                                    @if($nilai)
                                        <span class="badge {{ $nilaiAsli >= 3 ? 'badge-success' : ($nilaiAsli >= 2 ? 'badge-warning' : 'badge-info') }}">
                                            {{ $nilaiAsli }}/4
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($krit->bobot_roc * 100, 1) }}%
                                </td>
                                <td style="text-align: center;">
                                    @if($nilaiAsli >= 3)
                                        ✅ Baik
                                    @elseif($nilaiAsli >= 2)
                                        ⚠️ Cukup
                                    @else
                                        📈 Perlu Ditingkatkan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Status -->
            <div class="info-box" style="margin-top: 30px;">
                <h4 style="color: #ff6b35; margin-bottom: 15px;">Status Pencapaian</h4>
                @if($ranking == 1)
                    <p>🏆 <strong>Selamat!</strong> Ananda meraih prestasi sebagai <strong>SISWA TELADAN KELAS {{ $siswa->kelas }}</strong></p>
                @elseif($ranking <= 3)
                    <p>🥈 <strong>Luar Biasa!</strong> Ananda masuk dalam <strong>NOMINASI SISWA TELADAN</strong></p>
                @elseif($ranking <= 10)
                    <p>⭐ <strong>Bagus!</strong> Ananda masuk dalam <strong>10 BESAR KELAS</strong></p>
                @else
                    <p>📚 Terus semangat belajar dan tingkatkan prestasi!</p>
                @endif
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section">
                <h3 style="margin-bottom: 20px;">Akses Informasi Lebih Lengkap</h3>
                <p>Untuk melihat detail lengkap dan grafik perkembangan, silakan akses sistem kami:</p>
                <a href="{{ url('/informasi') }}" class="btn">Lihat Detail Lengkap</a>
                <a href="{{ url('/hasil-peringkat') }}" class="btn" style="background: #6c757d;">Lihat Peringkat Kelas</a>
            </div>
            
            <!-- Notes -->
            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="color: #856404; margin-bottom: 10px;">📝 Catatan Penting</h4>
                <ul style="margin: 0; padding-left: 20px; color: #856404;">
                    <li>Penilaian dilakukan secara objektif menggunakan metode ROC dan SMART</li>
                    <li>Data ini bersifat rahasia, mohon tidak disebarluaskan</li>
                    <li>Untuk konsultasi lebih lanjut, silakan hubungi wali kelas</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>SDIT As Sunnah Cirebon</strong></p>
            <p>Jl. Pendidikan No. 123, Cirebon</p>
            <p>📧 info@sditassunnah.sch.id | 📞 (0231) xxx-xxxx</p>
            
            <div class="social-links">
                <a href="#">Website</a> |
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">
                Email ini dikirim secara otomatis dari Sistem Pendukung Keputusan Siswa Teladan.<br>
                Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>