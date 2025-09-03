@props(['kriteria', 'index'])

@php
    $color = \App\Helpers\SPKHelper::getKriteriaColor($index);
    $icon = \App\Helpers\SPKHelper::getKriteriaIcon($kriteria->nama_kriteria);
@endphp

<div class="kriteria-card bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
    <div class="bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 p-4">
        <div class="flex items-center justify-between">
            <div>
                <i class="{{ $icon }} text-white text-2xl mb-2"></i>
                <h3 class="text-white font-bold text-lg">
                    {{ $kriteria->nama_kriteria }}
                </h3>
            </div>
            <div class="text-right">
                <div class="text-white text-2xl font-bold">
                    {{ number_format($kriteria->bobot_roc * 100, 1) }}%
                </div>
                <div class="text-white text-xs opacity-75">
                    Bobot
                </div>
            </div>
        </div>
    </div>
    
    <div class="p-4">
        <p class="text-gray-700 mb-4">{{ $kriteria->deskripsi }}</p>
        
        @if($kriteria->subKriteria->count() > 0)
        <div class="space-y-2 mb-4">
            <h4 class="font-semibold text-sm text-gray-600">Cara Penilaian:</h4>
            @foreach($kriteria->subKriteria->sortByDesc('nilai_utility') as $sub)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">{{ $sub->nama_sub_kriteria }}</span>
                <span class="px-2 py-1 bg-{{ $color }}-100 text-{{ $color }}-800 rounded">
                    Skor {{ $sub->nilai_utility }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
        
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>💡 Tips:</strong> 
                {{ $kriteria->tips ?? 'Konsultasikan dengan wali kelas untuk informasi lebih lanjut.' }}
            </p>
        </div>
        
        <button class="mt-3 text-{{ $color }}-600 text-sm font-semibold hover:text-{{ $color }}-800 transition">
            Pelajari Lebih Lanjut →
        </button>
    </div>
</div>