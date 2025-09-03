@extends('layouts.public')

@section('title', 'Hasil Peringkat Siswa Teladan')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Hasil Peringkat Siswa Teladan
            </h1>
            <p class="text-xl text-gray-600">
                Periode: {{ $periode->nama_periode ?? 'Belum Ada Periode Aktif' }}
            </p>
            
            <!-- Quick Links -->
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="{{ route('public.informasi') }}" 
                   class="px-4 py-2 bg-white text-blue-600 rounded-lg shadow hover:shadow-md transition">
                    <i class="fas fa-info-circle mr-2"></i> Pahami Sistem Penilaian
                </a>
                <button onclick="window.print()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    <i class="fas fa-print mr-2"></i> Cetak Hasil
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Kelas Filter -->
            <div class="flex flex-wrap gap-2">
                <button class="filter-btn px-4 py-2 rounded-lg border-2 border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white transition active" 
                        data-kelas="all">
                    Semua Kelas
                </button>
                @foreach(['6A', '6B', '6C', '6D'] as $kelas)
                <button class="filter-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-600 hover:border-blue-500 hover:text-blue-500 transition" 
                        data-kelas="{{ $kelas }}">
                    Kelas {{ $kelas }}
                </button>
                @endforeach
            </div>
            
            <!-- Search Box -->
            <div class="relative">
                <input type="text" 
                       id="searchSiswa" 
                       placeholder="Cari nama siswa..." 
                       class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>
</section>

<!-- Results Table -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($nilaiAkhir->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mr-3"></i>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800">Belum Ada Data</h3>
                    <p class="text-yellow-700">Data penilaian untuk periode ini belum tersedia.</p>
                </div>
            </div>
        </div>
        @else
        <!-- Top 3 Winners -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            @foreach($nilaiAkhir->take(3) as $index => $nilai)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition">
                <div class="bg-gradient-to-r from-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-400 to-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-500 p-4">
                    <div class="text-center">
                        <i class="fas fa-trophy text-white text-4xl mb-2"></i>
                        <h3 class="text-white font-bold text-xl">Peringkat {{ $index + 1 }}</h3>
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="font-bold text-lg mb-2">{{ $nilai->alternatif->nama }}</h4>
                    <p class="text-gray-600 mb-1">Kelas: {{ $nilai->alternatif->kelas }}</p>
                    <p class="text-gray-600 mb-3">NIS: {{ $nilai->alternatif->nis }}</p>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-sm text-blue-800">
                            <strong>Total Skor:</strong> {{ number_format($nilai->total, 3) }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Full Results Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600">
                <h2 class="text-2xl font-bold text-white">Daftar Lengkap Peringkat</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full" id="hasilTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peringkat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Siswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIS
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Skor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($nilaiAkhir as $index => $nilai)
                        <tr class="siswa-row hover:bg-gray-50" data-kelas="{{ $nilai->alternatif->kelas }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index < 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-400 text-white font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    @else
                                    <span class="text-gray-900 font-medium">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $nilai->alternatif->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $nilai->alternatif->kelas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $nilai->alternatif->nis }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-semibold">
                                    {{ number_format($nilai->total, 3) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($index < 10)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Siswa Teladan
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Peserta
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Informasi Penting
            </h3>
            <ul class="text-blue-700 space-y-1">
                <li>• Peringkat diperbarui setiap akhir semester</li>
                <li>• Penilaian menggunakan 6 kriteria utama dengan bobot yang berbeda</li>
                <li>• Untuk memahami sistem penilaian, kunjungi <a href="{{ route('public.informasi') }}" class="underline font-semibold">halaman informasi SPK</a></li>
                <li>• Data bersifat final dan tidak dapat diubah setelah periode penilaian selesai</li>
            </ul>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter by class
    const filterButtons = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.siswa-row');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active', 'border-blue-500', 'text-blue-500', 'bg-blue-500', 'text-white'));
            this.classList.add('active', 'border-blue-500', 'bg-blue-500', 'text-white');
            
            const selectedKelas = this.dataset.kelas;
            
            rows.forEach(row => {
                if (selectedKelas === 'all' || row.dataset.kelas === selectedKelas) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchSiswa');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
                const nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (nama.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>