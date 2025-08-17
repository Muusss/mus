@extends('layouts.public')

@section('title', 'Hasil Peringkat Siswa Teladan')

@push('styles')
  <!-- Fonts & DataTables CSS (selaras welcome) -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <style>
    :root{
      --primary-color:#ff6b35;   /* oranye (welcome) */
      --primary-dark:#e55100;
      --primary-light:#ff8c5a;
      --dark-color:#1a1a1a;
      --ink:#2c2c2c;
      --muted:#687280;
      --card:rgba(255,255,255,.95);
      --border:#e6e9ef;
      --ring:rgba(255,107,53,.18);
      --gold:#f59e0b; --silver:#94a3b8; --bronze:#d97706;
    }

    body{font-family:'Figtree',sans-serif}

    /* ==== Animated background & shapes (copy style welcome) ==== */
    .bg-animation{
      position:fixed; inset:0; z-index:-2;
      background:linear-gradient(-45deg,#ee7752,#e73c7e,#23a6d5,#23d5ab);
      background-size:400% 400%; animation:gradient 15s ease infinite;
    }
    @keyframes gradient{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}

    .shape{position:fixed; opacity:.08; z-index:-1}
    .shape-1{width:80px; height:80px; background:#fff; border-radius:50%; top:10%; left:10%; animation:float 6s ease-in-out infinite}
    .shape-2{width:120px; height:120px; background:#fff; border-radius:30% 70% 70% 30%/30% 30% 70% 70%; bottom:10%; right:10%; animation:float 8s ease-in-out infinite reverse}
    .shape-3{width:60px; height:60px; background:#fff; transform:rotate(45deg); top:50%; right:5%; animation:float 7s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-20px) rotate(180deg)}}

    /* ==== Page Header ==== */
    .page-header{display:flex;flex-direction:column;gap:.35rem}
    .page-title{margin:0; font-weight:800; color:#fff; display:flex; align-items:center; gap:.6rem}
    .page-title .text-warning{color:var(--gold)!important}
    .page-subtitle{margin:0;color:#f3f4f6}
    .badge-container{margin-top:.25rem}
    .badge-periode{
      display:inline-flex;align-items:center;gap:.5rem;
      background:linear-gradient(135deg,var(--primary-color),var(--primary-light));
      color:#fff;border-radius:999px;padding:.35rem .8rem;font-weight:700;font-size:.85rem;
      box-shadow:0 8px 24px rgba(0,0,0,.12)
    }

    /* ==== Glass wrapper ==== */
    .glass-wrap{
      background:var(--card); backdrop-filter:blur(20px);
      border:1px solid var(--border); border-radius:24px; padding:20px 18px;
      box-shadow:0 25px 50px rgba(0,0,0,.18);
    }

    /* ==== Filter ==== */
    .filter-card{ @extend .glass-wrap; } /* (komentar: hanya penanda, CSS nyata di bawah) */
    .filter-card{
      background:var(--card); border:1px solid var(--border); border-radius:18px; padding:14px 16px;
      box-shadow:0 16px 40px rgba(0,0,0,.10);
    }
    .filter-buttons{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:flex-end}
    .filter-btn{
      border:1px solid var(--border); background:#fff; color:var(--dark-color);
      padding:.5rem .9rem;border-radius:999px;font-weight:700; transition:.2s ease; cursor:pointer
    }
    .filter-btn:hover{border-color:var(--primary-color); box-shadow:0 0 0 .22rem var(--ring)}
    .filter-btn.active{background:linear-gradient(135deg,var(--primary-color),var(--primary-dark)); color:#fff; border-color:transparent}

    /* ==== Winner cards ==== */
    .winner-card{
      position:relative; background:var(--card); border:1px solid var(--border); border-radius:18px;
      padding:18px 16px; text-align:center; box-shadow:0 20px 50px rgba(0,0,0,.12); overflow:hidden
    }
    .winner-card::after{
      content:""; position:absolute; inset:0; pointer-events:none; opacity:.06;
      background:radial-gradient(800px 200px at 50% -10%, var(--primary-color), transparent);
    }
    .winner-badge{position:absolute; top:12px; right:12px; font-size:1.1rem}
    .winner-card.gold .winner-badge{color:var(--gold)}
    .winner-card.silver .winner-badge{color:var(--silver)}
    .winner-card.bronze .winner-badge{color:var(--bronze)}
    .winner-rank{font-weight:800; letter-spacing:.08em; color:var(--muted); font-size:.9rem; margin-top:.25rem}
    .winner-photo{display:flex; justify-content:center; margin:10px 0 12px}
    .photo-placeholder{
      width:96px;height:96px;border-radius:999px;background:#fff;
      border:3px solid transparent;
      background-image:linear-gradient(#fff,#fff), linear-gradient(135deg,var(--primary-color),var(--primary-light));
      background-origin:border-box;background-clip:content-box, border-box;
      display:flex;align-items:center;justify-content:center;color:#9aa4b2;font-size:2.2rem
    }
    .winner-name{font-weight:800;color:var(--dark-color);margin:6px 0 4px;font-size:1.15rem}
    .winner-info{display:flex;gap:.5rem;justify-content:center;flex-wrap:wrap;margin-bottom:8px}
    .info-item{
      display:inline-flex;align-items:center;gap:.35rem;
      background:#f6f7fb;color:var(--muted);border-radius:999px;padding:.25rem .6rem;font-weight:600;font-size:.8rem
    }
    .score-label{font-size:.8rem;color:var(--muted)}
    .score-value{
      font-weight:900;font-size:1.35rem;
      background:linear-gradient(135deg,var(--primary-color),var(--primary-dark));
      -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text
    }
    .winner-status{margin-top:8px;font-weight:800;color:#16a34a;font-size:.92rem}

    /* ==== Stat cards ==== */
    .stat-card{
      background:var(--card); border:1px solid var(--border); border-radius:18px; padding:16px;
      display:flex; gap:14px; align-items:center; box-shadow:0 16px 40px rgba(0,0,0,.10)
    }
    .stat-icon{
      width:46px;height:46px;border-radius:12px;display:grid;place-items:center;color:#fff;font-size:1.25rem;
      box-shadow:0 10px 18px rgba(0,0,0,.18)
    }
    .stat-icon.blue{background:linear-gradient(135deg,#60a5fa,#0ea5e9)}
    .stat-icon.green{background:linear-gradient(135deg,#34d399,#22c55e)}
    .stat-icon.orange{background:linear-gradient(135deg,#fbbf24,#f59e0b)}
    .stat-icon.red{background:linear-gradient(135deg,#fb7185,#ef4444)}
    .stat-content .stat-value{font-weight:900;color:var(--dark-color);font-size:1.35rem;line-height:1}
    .stat-content .stat-label{color:var(--muted);font-weight:600;font-size:.9rem}

    /* ==== Table card ==== */
    .table-card{
      background:var(--card); border:1px solid var(--border); border-radius:18px;
      box-shadow:0 20px 50px rgba(0,0,0,.12); overflow:hidden
    }
    .table-header{padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
    .table-header h4{margin:0;font-weight:900;color:var(--dark-color);display:flex;align-items:center;gap:.5rem}

    /* ==== Table ==== */
    .ranking-table{width:100%; border-collapse:separate; border-spacing:0}
    .ranking-table thead th{
      background:linear-gradient(180deg,#fafbff,#eef2f7);
      color:var(--muted); text-transform:uppercase; font-size:.78rem; letter-spacing:.06em;
      padding:12px; border-bottom:1px solid var(--border)
    }
    .ranking-table tbody td{padding:12px; border-bottom:1px solid var(--border); vertical-align:middle}
    .ranking-table tbody tr:hover{background:#fbfcff}
    .ranking-table tbody tr.top-three{background:rgba(255, 245, 200, .20)}
    .text-center{text-align:center}

    .rank-badge{
      display:inline-flex;align-items:center;gap:.35rem;border-radius:999px;padding:.25rem .6rem;font-weight:900
    }
    .rank-badge.default{background:#f2f5fa;color:var(--dark-color)}
    .rank-badge.gold{background:rgba(245,158,11,.14);color:var(--gold)}
    .rank-badge.silver{background:rgba(148,163,184,.16);color:var(--silver)}
    .rank-badge.bronze{background:rgba(217,119,6,.14);color:var(--bronze)}

    .gender-badge{display:inline-flex;align-items:center;gap:.25rem;border-radius:8px;padding:.25rem .5rem;font-weight:800;font-size:.8rem}
    .gender-badge.male{background:rgba(14,165,233,.12);color:#0284c7}
    .gender-badge.female{background:rgba(236,72,153,.12);color:#be185d}

    .class-badge{background:rgba(255,107,53,.12); color:var(--primary-dark); padding:.25rem .5rem; border-radius:8px; font-weight:800}
    .score-badge{background:rgba(255,107,53,.12); color:var(--primary-dark); padding:.25rem .6rem; border-radius:999px; font-weight:900}

    .status-badge{display:inline-flex;align-items:center;gap:.35rem;border-radius:10px;padding:.25rem .55rem;font-weight:800}
    .status-badge.teladan{background:rgba(34,197,94,.12);color:#16a34a}
    .status-badge.nominasi{background:rgba(245,158,11,.12);color:#b45309}
    .status-badge.top10{background:rgba(59,130,246,.12);color:#1d4ed8}
    .status-badge.partisipan{background:rgba(100,116,139,.15);color:#475569}

    /* ==== Empty state ==== */
    .empty-state{
      background:var(--card); border:1px solid var(--border); border-radius:18px;
      padding:40px; text-align:center; box-shadow:0 20px 50px rgba(0,0,0,.12)
    }
    .empty-state i{font-size:48px;color:#a0a8b5}
    .empty-state h3{margin:12px 0 6px;font-weight:900;color:var(--dark-color)}
    .empty-state p{color:var(--muted);margin-bottom:16px}
    .btn-back{
      display:inline-flex;align-items:center;gap:.5rem;
      background:linear-gradient(135deg,var(--primary-color),var(--primary-dark));
      color:#fff;text-decoration:none;border-radius:12px;padding:.55rem .9rem;font-weight:900
    }
    .btn-back:hover{opacity:.95}

    /* ==== DataTables polish ==== */
    .dataTables_wrapper .dataTables_filter input{
      border:1px solid var(--border);border-radius:999px;padding:.35rem .7rem;margin-left:.4rem;outline:none
    }
    .dataTables_wrapper .dataTables_length select{
      border:1px solid var(--border);border-radius:10px;padding:.25rem .45rem;margin:0 .35rem
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button{
      border:1px solid var(--border)!important;border-radius:10px!important;background:#fff!important;margin:0 .15rem!important
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
      background:linear-gradient(135deg,var(--primary-color),var(--primary-dark))!important;color:#fff!important;border-color:transparent!important
    }

    /* ==== Responsive cards on mobile (tanpa responsive plugin) ==== */
    @media (max-width: 767.98px){
      .page-title{font-size:1.35rem}
      .filter-card{padding:12px}
      .table-header{padding:12px}
      .ranking-table thead{display:none}
      .ranking-table, .ranking-table tbody, .ranking-table tr, .ranking-table td{display:block;width:100%}
      .ranking-table tbody tr{margin-bottom:12px;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff}
      .ranking-table tbody td{display:flex;justify-content:space-between;gap:10px;padding:10px 12px}
      .ranking-table tbody td::before{content:attr(data-label);font-weight:700;color:var(--muted)}
      .text-center{justify-content:center}
    }
  </style>
@endpush

@section('content')
  <!-- Background & shapes (match welcome) -->
  <div class="bg-animation"></div>
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  <div class="shape shape-3"></div>

  <div class="container-fluid px-4" style="position:relative; z-index:1;">
    <!-- Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="page-header">
          <h1 class="page-title">
            <i class="bi bi-trophy-fill text-warning"></i>
            Hasil Peringkat Siswa Teladan
          </h1>
          <p class="page-subtitle">SDIT As Sunnah Cirebon - Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</p>
          @if($periodeAktif)
            <div class="badge-container">
              <span class="badge-periode">{{ $periodeAktif->nama_periode }}</span>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="filter-card">
          <div class="row align-items-center g-2">
            <div class="col-md-6">
              <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-funnel"></i> Filter Kelas
              </h5>
            </div>
            <div class="col-md-6">
              <div class="filter-buttons">
                <button class="filter-btn {{ $kelasFilter == 'all' ? 'active' : '' }}" onclick="filterByKelas('all')">
                  <i class="bi bi-grid-3x3-gap"></i> Semua Kelas
                </button>
                @foreach($kelasList as $kelas)
                  <button class="filter-btn {{ $kelasFilter == $kelas ? 'active' : '' }}" onclick="filterByKelas('{{ $kelas }}')">
                    Kelas {{ $kelas }}
                  </button>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if($nilaiAkhir && $nilaiAkhir->count() > 0)
      <!-- Top 3 -->
      <div class="row mb-4">
        @php
          $top3 = $nilaiAkhir->take(3);
          $medals = ['gold','silver','bronze'];
          $icons  = ['trophy-fill','award-fill','award'];
        @endphp
        @foreach($top3 as $index => $siswa)
          <div class="col-lg-4 mb-3">
            <div class="winner-card {{ $medals[$index] }}">
              <div class="winner-badge"><i class="bi bi-{{ $icons[$index] }}"></i></div>
              <div class="winner-rank">JUARA {{ $index + 1 }}</div>
              <div class="winner-photo">
                <div class="photo-placeholder"><i class="bi bi-person-circle"></i></div>
              </div>
              <h3 class="winner-name">{{ $siswa->alternatif->nama_siswa ?? '-' }}</h3>
              <div class="winner-info">
                <span class="info-item"><i class="bi bi-card-text"></i> NIS: {{ $siswa->alternatif->nis ?? '-' }}</span>
                <span class="info-item"><i class="bi bi-building"></i> {{ $siswa->alternatif->kelas ?? '-' }}</span>
              </div>
              <div class="winner-score">
                <span class="score-label">Total Nilai</span>
                <span class="score-value">{{ number_format($siswa->total ?? 0, 4) }}</span>
              </div>
              @if($kelasFilter && $kelasFilter !== 'all')
                <div class="winner-status">Siswa Teladan Kelas {{ $kelasFilter }}</div>
              @else
                <div class="winner-status">Siswa Teladan Sekolah</div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Statistik -->
      <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
          <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-content">
              <div class="stat-value">{{ $nilaiAkhir->count() }}</div>
              <div class="stat-label">Total Siswa</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
          <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-content">
              <div class="stat-value">{{ number_format($nilaiAkhir->max('total') ?? 0, 4) }}</div>
              <div class="stat-label">Nilai Tertinggi</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
          <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-calculator"></i></div>
            <div class="stat-content">
              <div class="stat-value">{{ number_format($nilaiAkhir->avg('total') ?? 0, 4) }}</div>
              <div class="stat-label">Nilai Rata-rata</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
          <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-graph-down-arrow"></i></div>
            <div class="stat-content">
              <div class="stat-value">{{ number_format($nilaiAkhir->min('total') ?? 0, 4) }}</div>
              <div class="stat-label">Nilai Terendah</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel -->
      <div class="row">
        <div class="col-12">
          <div class="table-card">
            <div class="table-header">
              <h4>
                <i class="bi bi-list-ol"></i>
                Tabel Peringkat Lengkap
                @if($kelasFilter && $kelasFilter !== 'all') - Kelas {{ $kelasFilter }} @endif
              </h4>
            </div>
            <div class="table-responsive">
              <table id="rankingTable" class="ranking-table display">
                <thead>
                  <tr>
                    <th width="80">Peringkat</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>L/P</th>
                    <th>Kelas</th>
                    <th>Total Nilai</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($nilaiAkhir as $row)
                  <tr class="{{ $row->peringkat_kelas <= 3 ? 'top-three' : '' }}">
                    <td class="text-center" data-label="Peringkat">
                      @if($row->peringkat_kelas == 1)
                        <span class="rank-badge gold"><i class="bi bi-trophy-fill"></i> 1</span>
                      @elseif($row->peringkat_kelas == 2)
                        <span class="rank-badge silver"><i class="bi bi-award-fill"></i> 2</span>
                      @elseif($row->peringkat_kelas == 3)
                        <span class="rank-badge bronze"><i class="bi bi-award"></i> 3</span>
                      @else
                        <span class="rank-badge default">{{ $row->peringkat_kelas }}</span>
                      @endif
                    </td>
                    <td data-label="NIS">{{ $row->alternatif->nis ?? '-' }}</td>
                    <td data-label="Nama Siswa">
                      <strong>{{ $row->alternatif->nama_siswa ?? '-' }}</strong>
                      @if($row->peringkat_kelas == 1)
                        <i class="bi bi-star-fill" style="color:var(--gold);margin-left:.25rem"></i>
                      @endif
                    </td>
                    <td data-label="L/P">
                      @if($row->alternatif->jk == 'Lk')
                        <span class="gender-badge male"><i class="bi bi-gender-male"></i> L</span>
                      @else
                        <span class="gender-badge female"><i class="bi bi-gender-female"></i> P</span>
                      @endif
                    </td>
                    <td data-label="Kelas"><span class="class-badge">{{ $row->alternatif->kelas ?? '-' }}</span></td>
                    <td class="text-center" data-label="Total Nilai">
                      <span class="score-badge">{{ number_format($row->total ?? 0, 4) }}</span>
                    </td>
                    <td data-label="Status">
                      @if($row->peringkat_kelas == 1)
                        <span class="status-badge teladan"><i class="bi bi-star-fill"></i> Siswa Teladan</span>
                      @elseif($row->peringkat_kelas <= 3)
                        <span class="status-badge nominasi"><i class="bi bi-award"></i> Nominasi</span>
                      @elseif($row->peringkat_kelas <= 10)
                        <span class="status-badge top10">10 Besar</span>
                      @else
                        <span class="status-badge partisipan">Partisipan</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Empty state -->
      <div class="row">
        <div class="col-12">
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum Ada Data Peringkat</h3>
            <p>Data peringkat siswa teladan belum tersedia untuk periode ini.</p>
            <a href="{{ url('/') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection

@section('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    function filterByKelas(kelas){
      window.location.href = '{{ route("hasil.publik") }}?kelas=' + kelas;
    }
    $(function(){
      @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
      $('#rankingTable').DataTable({
        responsive:true,
        pageLength:25,
        order:[[0,'asc']],
        language:{
          search:"Cari:",
          lengthMenu:"Tampilkan _MENU_ data",
          info:"Menampilkan _START_–_END_ dari _TOTAL_ data",
          paginate:{ first:"Pertama", last:"Terakhir", next:"Selanjutnya", previous:"Sebelumnya" },
          zeroRecords:"Tidak ada data yang cocok",
          infoEmpty:"Tidak ada data",
          infoFiltered:"(disaring dari _MAX_ total data)"
        }
      });
      @endif
    });
  </script>
@endsection
