@extends('layouts.public')

@section('title', 'Selamat Datang - SDIT As Sunnah Cirebon')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-orange-500 to-orange-600 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-5xl font-bold mb-4 animate-fade-in">
                Sistem Penilaian Siswa Teladan
            </h1>
            <p class="text-2xl mb-2">SDIT As Sunnah Cirebon</p>
            <p class="text-lg opacity-90">
                Transparansi dan Objektivitas dalam Penilaian
            </p>
            
            @if($periode)
            <div class="mt-6">
                <span class="inline-block px-6 py-3 bg-white text-orange-600 rounded-full font-semibold">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Periode Aktif: {{ $periode->nama_periode }}
                </span>
            </div>
            @endif
        </div>
        
        <!-- Quick Stats -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/10 backdrop-blur rounded-lg p-6 text-center hover:bg-white/20 transition">
                <div class="text-4xl font-bold animate-number" data-target="{{ $totalSiswa ?? 0 }}">0</div>
                <div class="text-lg">Total Siswa</div>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-lg p-6 text-center hover:bg-white/20 transition">
                <div class="text-4xl font-bold animate-number" data-target="{{ $totalKriteria ?? 6 }}">0</div>
                <div class="text-lg">Kriteria Penilaian</div>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-lg p-6 text-center hover:bg-white/20 transition">
                <div class="text-4xl font-bold animate-number" data-target="100">0</div>
                <div class="text-lg">% Transparansi</div>
            </div>
        </div>
        
        <!-- CTA Buttons -->
        <div class="flex flex-wrap justify-center gap-4 mt-12">
            <a href="{{ route('public.informasi') }}" 
               class="px-8 py-4 bg-white text-orange-600 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 font-semibold text-lg">
                <i class="fas fa-info-circle mr-2"></i>
                Pelajari Sistem Penilaian
            </a>
            <a href="{{ route('hasil.publik') }}" 
               class="px-8 py-4 bg-orange-700 text-white rounded-lg shadow-lg hover:shadow-xl hover:bg-orange-800 transform hover:-translate-y-1 transition-all duration-300 font-semibold text-lg">
                <i class="fas fa-trophy mr-2"></i>
                Lihat Hasil Peringkat
            </a>
        </div>
    </div>
    
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-400 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-orange-300 rounded-full opacity-20 animate-pulse delay-1000"></div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Mengapa Sistem Ini Penting?</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition card-hover">
                <div class="text-orange-500 text-4xl mb-4">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Penilaian Objektif</h3>
                <p class="text-gray-600">
                    Menggunakan metode ROC-SMART yang terukur dan adil untuk semua siswa
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition card-hover">
                <div class="text-orange-500 text-4xl mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Perkembangan Holistik</h3>
                <p class="text-gray-600">
                    Menilai 6 aspek penting: akademik, agama, akhlak, hafalan, kehadiran, dan ekstrakurikuler
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition card-hover">
                <div class="text-orange-500 text-4xl mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Transparansi Total</h3>
                <p class="text-gray-600">
                    Orang tua dapat memahami proses penilaian dan memantau perkembangan anak
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Bagaimana Sistem Bekerja?</h2>
        
        <div class="max-w-4xl mx-auto">
            <div class="relative">
                <!-- Timeline -->
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-orange-300"></div>
                
                <!-- Steps -->
                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="bg-orange-500 text-white rounded-full w-16 h-16 flex items-center justify-center font-bold text-xl z-10">
                            1
                        </div>
                        <div class="ml-8 bg-gray-50 p-6 rounded-lg flex-1">
                            <h3 class="font-bold text-lg mb-2">Pengumpulan Data</h3>
                            <p class="text-gray-600">Guru mengumpulkan data nilai dari berbagai aspek penilaian</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-orange-500 text-white rounded-full w-16 h-16 flex items-center justify-center font-bold text-xl z-10">
                            2
                        </div>
                        <div class="ml-8 bg-gray-50 p-6 rounded-lg flex-1">
                            <h3 class="font-bold text-lg mb-2">Pembobotan ROC</h3>
                            <p class="text-gray-600">Sistem memberikan bobot pada setiap kriteria sesuai prioritas</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-orange-500 text-white rounded-full w-16 h-16 flex items-center justify-center font-bold text-xl z-10">
                            3
                        </div>
                        <div class="ml-8 bg-gray-50 p-6 rounded-lg flex-1">
                            <h3 class="font-bold text-lg mb-2">Perhitungan SMART</h3>
                            <p class="text-gray-600">Nilai dinormalisasi dan dihitung menggunakan metode SMART</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-orange-500 text-white rounded-full w-16 h-16 flex items-center justify-center font-bold text-xl z-10">
                            4
                        </div>
                        <div class="ml-8 bg-gray-50 p-6 rounded-lg flex-1">
                            <h3 class="font-bold text-lg mb-2">Hasil Peringkat</h3>
                            <p class="text-gray-600">Peringkat final ditampilkan secara transparan untuk semua pihak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Cards Section -->
<section class="py-16 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- For Parents Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-user-friends mr-2"></i>
                        Untuk Orang Tua
                    </h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Pahami kriteria penilaian anak Anda</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Lihat posisi dan perkembangan anak</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Dapatkan tips meningkatkan prestasi</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Simulasi perhitungan nilai</span>
                        </li>
                    </ul>
                    <a href="{{ route('public.informasi') }}" 
                       class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        Pelajari Lebih Lanjut →
                    </a>
                </div>
            </div>
            
            <!-- For Teachers Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Untuk Guru
                    </h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Input nilai dengan mudah</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Perhitungan otomatis dan akurat</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Laporan lengkap dan terstruktur</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Export data ke PDF</span>
                        </li>
                    </ul>
                    <a href="{{ route('login') }}" 
                       class="mt-6 inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                        Login Dashboard →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Preview Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Pertanyaan Umum</h2>
        
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">
                    <i class="fas fa-question-circle text-orange-500 mr-2"></i>
                    Apakah sistem ini adil untuk semua siswa?
                </h3>
                <p class="text-gray-600">
                    Ya, sistem menggunakan metode ilmiah ROC-SMART yang objektif dan terukur. 
                    Semua siswa dinilai dengan kriteria dan bobot yang sama.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">
                    <i class="fas fa-question-circle text-orange-500 mr-2"></i>
                    Bagaimana cara melihat nilai anak saya?
                </h3>
                <p class="text-gray-600">
                    Anda dapat mencari data anak menggunakan NIS dan nama lengkap di halaman informasi SPK. 
                    Data ditampilkan dengan menjaga privasi siswa lain.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">
                    <i class="fas fa-question-circle text-orange-500 mr-2"></i>
                    Kapan hasil penilaian diperbarui?
                </h3>
                <p class="text-gray-600">
                    Hasil penilaian diperbarui setiap akhir semester (2 kali setahun) untuk memastikan data yang akurat.
                </p>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('public.informasi') }}" 
                   class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                    Lihat Semua FAQ →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Apa Kata Mereka?</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-orange-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold">Bapak Ahmad</h4>
                        <p class="text-sm text-gray-500">Orang Tua Siswa</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Sistem ini sangat membantu kami memahami perkembangan anak. Transparansi penilaian membuat kami bisa fokus mendampingi anak di area yang perlu ditingkatkan."
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-orange-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold">Ibu Fatimah</h4>
                        <p class="text-sm text-gray-500">Orang Tua Siswa</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Alhamdulillah, dengan sistem ini kami bisa melihat bahwa penilaian dilakukan secara objektif dan adil untuk semua siswa."
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-orange-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold">Ustadzah Sarah</h4>
                        <p class="text-sm text-gray-500">Guru Kelas 6</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Sistem SPK memudahkan kami dalam melakukan penilaian yang komprehensif dan menghemat waktu dalam perhitungan."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-20 bg-gradient-to-br from-orange-500 to-orange-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6">
            Mulai Pahami Sistem Penilaian Anak Anda
        </h2>
        <p class="text-xl mb-8 opacity-90">
            Dapatkan informasi lengkap tentang kriteria, bobot, dan cara penilaian
        </p>
        
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('public.informasi') }}" 
               class="px-8 py-4 bg-white text-orange-600 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 font-semibold text-lg">
                <i class="fas fa-book-open mr-2"></i>
                Baca Panduan Lengkap
            </a>
            <a href="{{ route('hasil.publik') }}" 
               class="px-8 py-4 bg-orange-700 text-white rounded-lg shadow-lg hover:shadow-xl hover:bg-orange-800 transform hover:-translate-y-1 transition-all duration-300 font-semibold text-lg">
                <i class="fas fa-search mr-2"></i>
                Cari Data Anak
            </a>
        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 0.2;
        }
        50% {
            opacity: 0.3;
        }
    }
    
    .animate-pulse {
        animation: pulse 3s ease-in-out infinite;
    }
    
    .delay-1000 {
        animation-delay: 1s;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate numbers
    const animateNumbers = document.querySelectorAll('.animate-number');
    
    const animateValue = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.textContent = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                // Add % sign for percentage
                if (element.dataset.target == 100) {
                    element.textContent = element.textContent + '%';
                }
            }
        };
        window.requestAnimationFrame(step);
    };
    
    // Intersection Observer for animation trigger
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const target = parseInt(element.dataset.target);
                animateValue(element, 0, target, 2000);
                observer.unobserve(element);
            }
        });
    }, {
        threshold: 0.5
    });
    
    animateNumbers.forEach(element => {
        observer.observe(element);
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add hover effect to cards
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
});
</script>
@endsection