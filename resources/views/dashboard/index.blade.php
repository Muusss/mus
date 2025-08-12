@extends('dashboard.layouts.main')

@section('content')
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Jumlah Siswa</div>
            <div class="fs-3 fw-semibold">{{ $jumlahSiswa ?? 0 }}</div>
          </div>
          <i class="bi bi-people fs-2 text-primary"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Jumlah Kriteria</div>
            <div class="fs-3 fw-semibold">{{ $jumlahKriteria ?? 0 }}</div>
          </div>
          <i class="bi bi-list-check fs-2 text-success"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Entri Penilaian</div>
            <div class="fs-3 fw-semibold">{{ $jumlahPenilaian ?? 0 }}</div>
          </div>
          <i class="bi bi-clipboard-data fs-2 text-warning"></i>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Chart peringkat --}}
<div class="card mb-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h5 class="mb-0">Grafik Total Nilai (ROC + SMART)</h5>
    </div>
    <div id="chart_peringkat" style="min-height: 320px;"></div>
  </div>
</div>

{{-- Tabel ranking --}}
<div class="card">
  <div class="card-body">
    <h5 class="mb-3">Peringkat Siswa</h5>
    <div class="table-responsive">
      <table id="tblRanking" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Total</th>
            <th>Peringkat</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($nilaiAkhir as $row)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $row->alternatif->nis ?? '-' }}</td>
              <td>{{ $row->alternatif->nama_siswa ?? 'Siswa '.$row->alternatif_id }}</td>
              <td>{{ $row->alternatif->kelas ?? '-' }}</td>
              <td>{{ number_format((float)$row->total, 6) }}</td>
              <td>{{ $row->peringkat ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Belum ada hasil perhitungan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  // ==== Data untuk chart dari controller ====
  const labels = @json($chartLabels ?? []);
  const seriesData = @json($chartSeries ?? []);

  // ==== ApexCharts (bar) ====
  if (window.ApexCharts) {
    const options = {
      chart: { type: 'bar', height: 320, toolbar: { show: false } },
      series: [{ name: 'Total', data: seriesData }],
      xaxis: { categories: labels, labels: { rotate: -45 } },
      dataLabels: { enabled: false },
      stroke: { show: true, width: 1 },
      plotOptions: { bar: { borderRadius: 4 } },
      tooltip: {
        y: { formatter: function (val) { return (Number(val) || 0).toFixed(3); } }
      }
    };
    const chart = new ApexCharts(document.querySelector("#chart_peringkat"), options);
    chart.render();
  }

  // ==== DataTables untuk ranking ====
  $(function () {
    $('#tblRanking').DataTable({
      responsive: true,
      pagingType: 'full_numbers',
      order: [[4, 'desc']], // urut berdasarkan kolom "Total"
    });
  });
</script>
@endsection
