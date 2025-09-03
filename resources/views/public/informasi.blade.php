@extends('layouts.public')

@section('title', 'Informasi SPK Siswa Teladan - SDIT As Sunnah Cirebon')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-orange-50 to-orange-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Informasi Sistem Penilaian Siswa Teladan
            </h1>
            <p class="text-xl text-gray-600">Panduan Lengkap untuk Orang Tua/Wali Siswa</p>
            @if($periodeAktif)
            <span class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded-full">
                Periode Aktif: {{ $periodeAktif->nama_periode }}
            </span>
            @endif
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-wrap justify-center gap-4 mt-8">
            <a href="{{ route('hasil.publik') }}"
               class="px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                <i class="fas fa-trophy mr-2"></i> Lihat Hasil Peringkat
            </a>
            <a href="{{ route('login') }}" 
               class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login Guru/Admin
            </a>
        </div>
    </div>
</section>

<!-- Sambutan Section -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <span class="text-green-600">Assalamu'alaikum</span> Ayah Bunda...
                </h2>
                <p class="text-gray-700 leading-relaxed">
                    Kami dengan bangga memperkenalkan Sistem Penilaian Siswa Teladan yang objektif dan transparan. 
                    Sistem ini membantu sekolah menilai putra-putri Anda berdasarkan 6 aspek penting yang mencerminkan 
                    nilai-nilai Islam dan prestasi akademik.
                </p>
            </div>
        </div>

        <!-- Manfaat Cards -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="text-4xl mb-4">👀</div>
                <h3 class="text-xl font-bold mb-2">Transparansi Penuh</h3>
                <ul class="text-gray-600 space-y-1">
                    <li>• Semua kriteria dan cara penilaian terbuka</li>
                    <li>• Tidak ada penilaian tersembunyi</li>
                    <li>• Orang tua dapat memahami proses penilaian</li>
                </ul>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                <div class="text-4xl mb-4">📊</div>
                <h3 class="text-xl font-bold mb-2">Penilaian Objektif</h3>
                <ul class="text-gray-600 space-y-1">
                    <li>• Menggunakan sistem komputer yang adil</li>
                    <li>• Tidak ada favoritisme atau subjektivitas</li>
                    <li>• Semua siswa dinilai dengan standar yang sama</li>
                </ul>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-orange-500">
                <div class="text-4xl mb-4">📈</div>
                <h3 class="text-xl font-bold mb-2">Motivasi Positif</h3>
                <ul class="text-gray-600 space-y-1">
                    <li>• Membantu anak fokus pada area yang perlu diperbaiki</li>
                    <li>• Memberikan apresiasi pada prestasi yang dicapai</li>
                    <li>• Mendorong perkembangan holistik anak</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Penjelasan Sistem SPK -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Memahami Sistem SPK</h2>
            
            <!-- Apa itu SPK -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex items-start">
                    <div class="text-3xl mr-4">💻</div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Apa itu SPK?</h3>
                        <p class="text-gray-700">
                            SPK adalah sistem komputer pintar yang membantu guru menilai siswa secara adil. 
                            Bayangkan seperti kalkulator canggih yang menggabungkan semua aspek penilaian anak Anda - 
                            dari nilai akademik, akhlak, hafalan Qur'an, hingga keaktifan di sekolah.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Metode ROC-SMART -->
            <div class="space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-3">🔢 ROC (Rank Order Centroid)</h3>
                    <p class="text-gray-700">
                        Sistem yang menentukan mana kriteria yang paling penting. Misalnya, nilai akademik 
                        diberi bobot lebih tinggi daripada ekstrakurikuler karena ini adalah sekolah dasar.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-3">⚖️ SMART (Simple Multi Attribute Rating)</h3>
                    <p class="text-gray-700">
                        Sistem yang mengubah semua nilai menjadi skala yang sama (1-4), sehingga nilai raport 
                        bisa dibandingkan dengan hafalan Qur'an secara adil.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-3">✨ Hasil Akhir</h3>
                    <p class="text-gray-700">
                        Kombinasi kedua sistem menghasilkan skor final yang objektif untuk menentukan 
                        peringkat siswa teladan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kriteria Penilaian -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-3">6 Aspek Penilaian Anak Anda</h2>
            <p class="text-gray-600">Setiap aspek memiliki bobot dan cara penilaian yang jelas</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kriteria as $k)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-{{ $loop->iteration == 1 ? 'blue' : ($loop->iteration == 2 ? 'green' : ($loop->iteration == 3 ? 'purple' : ($loop->iteration == 4 ? 'yellow' : ($loop->iteration == 5 ? 'red' : 'indigo')))) }}-500 to-{{ $loop->iteration == 1 ? 'blue' : ($loop->iteration == 2 ? 'green' : ($loop->iteration == 3 ? 'purple' : ($loop->iteration == 4 ? 'yellow' : ($loop->iteration == 5 ? 'red' : 'indigo')))) }}-600 p-4">
                    <h3 class="text-white font-bold text-lg">
                        {{ $k->nama_kriteria }}
                    </h3>
                    <div class="text-white text-sm mt-1">
                        Bobot: {{ number_format($k->bobot_roc * 100, 1) }}%
                    </div>
                </div>
                
                <div class="p-4">
                    <p class="text-gray-700 mb-4">{{ $k->deskripsi }}</p>
                    
                    @if($k->subKriteria->count() > 0)
                    <div class="space-y-2">
                        <h4 class="font-semibold text-sm">Cara Penilaian:</h4>
                        @foreach($k->subKriteria->sortByDesc('nilai_utility') as $sub)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $sub->nama_sub_kriteria }}</span>
                            <span class="px-2 py-1 bg-gray-100 rounded">
                                Skor {{ $sub->nilai_utility }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded">
                        <p class="text-sm text-blue-800">
                            <strong>💡 Tips:</strong> 
                            @switch($loop->iteration)
                                @case(1)
                                    Dukung anak dengan belajar rutin 1-2 jam setiap hari di rumah
                                    @break
                                @case(2)
                                    Ajak anak mengulang pelajaran agama dan berikan teladan akhlak di rumah
                                    @break
                                @case(3)
                                    Berikan contoh akhlak mulia di rumah dan koreksi dengan lembut
                                    @break
                                @case(4)
                                    Dampingi anak murajaah (mengulang) hafalan 15-30 menit setiap hari
                                    @break
                                @case(5)
                                    Pastikan anak tidur cukup dan siapkan kebutuhan sekolah dari malam hari
                                    @break
                                @case(6)
                                    Dorong anak memilih minimal 1 ekstrakurikuler sesuai minat dan bakatnya
                                    @break
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Simulator Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Simulator Nilai Anak Anda</h2>
            
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-gray-600 mb-6">
                    Masukkan nilai hipotesis untuk melihat prediksi skor akhir anak Anda:
                </p>
                
                <div id="simulator" class="space-y-4">
                    @foreach($kriteria as $k)
                    <div class="flex items-center justify-between">
                        <label class="font-medium w-1/3">{{ $k->nama_kriteria }}</label>
                        <input type="range" 
                               class="simulator-input flex-1 mx-4" 
                               data-kriteria="{{ $k->id }}"
                               data-bobot="{{ $k->bobot_roc }}"
                               min="1" max="4" step="0.1" value="2.5">
                        <span class="simulator-value w-16 text-center font-bold">2.5</span>
                    </div>
                    @endforeach
                    
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-gray-600">Prediksi Skor Akhir:</p>
                            <p class="text-3xl font-bold text-blue-600" id="total-score">0.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Panduan untuk Orang Tua -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">
                Cara Membantu Anak Meningkatkan Prestasi
            </h2>
            
            <div class="space-y-4">
                <!-- Accordion Items -->
                <div class="accordion-item border rounded-lg">
                    <button class="accordion-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-bold">📚 Meningkatkan Nilai Akademik</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content hidden p-4 border-t bg-gray-50">
                        <ul class="space-y-2 text-gray-700">
                            <li>• Buat jadwal belajar rutin di rumah</li>
                            <li>• Sediakan ruang belajar yang nyaman</li>
                            <li>• Dampingi saat mengerjakan PR</li>
                            <li>• Komunikasi dengan wali kelas untuk mengetahui area yang lemah</li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item border rounded-lg">
                    <button class="accordion-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-bold">🤲 Membina Akhlak di Rumah</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content hidden p-4 border-t bg-gray-50">
                        <ul class="space-y-2 text-gray-700">
                            <li>• Berikan teladan perilaku Islami setiap hari</li>
                            <li>• Ajarkan adab makan, berbicara, dan bergaul</li>
                            <li>• Koreksi dengan lembut saat anak melakukan kesalahan</li>
                            <li>• Apresiasi saat anak menunjukkan akhlak baik</li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item border rounded-lg">
                    <button class="accordion-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-bold">🕌 Mendukung Hafalan Al-Qur'an</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content hidden p-4 border-t bg-gray-50">
                        <ul class="space-y-2 text-gray-700">
                            <li>• Sediakan waktu murajaah 15-30 menit setiap hari</li>
                            <li>• Dengarkan hafalan anak dengan sabar</li>
                            <li>• Gunakan aplikasi Qur'an untuk membantu</li>
                            <li>• Berikan reward sederhana saat target tercapai</li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item border rounded-lg">
                    <button class="accordion-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-bold">📅 Melatih Kedisiplinan</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content hidden p-4 border-t bg-gray-50">
                        <ul class="space-y-2 text-gray-700">
                            <li>• Tetapkan waktu tidur dan bangun yang konsisten</li>
                            <li>• Siapkan peralatan sekolah dari malam hari</li>
                            <li>• Berikan konsekuensi positif untuk ketepatan waktu</li>
                            <li>• Jadilah contoh disiplin waktu di rumah</li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item border rounded-lg">
                    <button class="accordion-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-bold">🎯 Mendorong Ekstrakurikuler</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content hidden p-4 border-t bg-gray-50">
                        <ul class="space-y-2 text-gray-700">
                            <li>• Tanyakan minat dan bakat anak</li>
                            <li>• Dukung pilihan ekstrakurikuler anak</li>
                            <li>• Hadiri acara atau kompetisi yang diikuti anak</li>
                            <li>• Jangan memaksakan jika anak belum siap</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">
                Pertanyaan yang Sering Diajukan (FAQ)
            </h2>
            
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow-md">
                    <button class="faq-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold">❓ Seberapa sering penilaian diperbarui?</span>
                        <i class="fas fa-plus text-gray-500"></i>
                    </button>
                    <div class="faq-content hidden p-4 border-t">
                        <p class="text-gray-700">
                            Setiap akhir semester (2 kali setahun) untuk memastikan data yang akurat 
                            dan memberikan waktu siswa untuk perbaikan.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="faq-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold">❓ Apakah peringkat anak saya dilihat orang tua lain?</span>
                        <i class="fas fa-plus text-gray-500"></i>
                    </button>
                    <div class="faq-content hidden p-4 border-t">
                        <p class="text-gray-700">
                            Tidak. Setiap orang tua hanya dapat melihat data anaknya sendiri melalui 
                            pencarian dengan NIS. Hasil publik hanya menampilkan nama tanpa detail nilai.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="faq-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold">❓ Bagaimana jika anak saya tidak masuk 10 besar?</span>
                        <i class="fas fa-plus text-gray-500"></i>
                    </button>
                    <div class="faq-content hidden p-4 border-t">
                        <p class="text-gray-700">
                            Setiap anak memiliki keunikan dan kelebihan masing-masing. Sistem ini bukan 
                            untuk membuat kompetisi, tetapi untuk memotivasi perbaikan di semua aspek.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="faq-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold">❓ Bisakah kriteria penilaian berubah?</span>
                        <i class="fas fa-plus text-gray-500"></i>
                    </button>
                    <div class="faq-content hidden p-4 border-t">
                        <p class="text-gray-700">
                            Kriteria ditetapkan di awal tahun ajaran dan tidak berubah selama 1 tahun 
                            untuk menjaga konsistensi. Evaluasi kriteria dilakukan setiap pergantian tahun ajaran.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="faq-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold">❓ Apa itu skor utility dalam SMART?</span>
                        <i class="fas fa-plus text-gray-500"></i>
                    </button>
                    <div class="faq-content hidden p-4 border-t">
                        <p class="text-gray-700">
                            Skor utility adalah nilai yang sudah dinormalisasi (0-1) sehingga semua 
                            kriteria dapat dibandingkan secara adil.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="faq-header w-full p-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold">❓ Bagaimana bobot ROC dihitung?</span>
                        <i class="fas fa-plus text-gray-500"></i>
                    </button>
                    <div class="faq-content hidden p-4 border-t">
                        <p class="text-gray-700">
                            Bobot dihitung otomatis berdasarkan ranking kepentingan kriteria yang telah 
                            ditentukan guru senior dan kepala sekolah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pencarian Data Anak -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-center mb-6">
                    Cari Data Anak Anda
                </h2>
                
                <form id="searchForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            NIS (Nomor Induk Siswa)
                        </label>
                        <input type="text" 
                               name="nis" 
                               id="nis"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Masukkan NIS anak Anda"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" 
                               name="nama" 
                               id="nama"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Masukkan nama lengkap anak"
                               required>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i> Cari Data Anak
                    </button>
                </form>
                
                <div id="searchResult" class="mt-6 hidden">
                    <!-- Results will be displayed here -->
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-lock mr-1"></i>
                        <strong>Privacy Note:</strong> Data hanya ditampilkan setelah verifikasi identitas. 
                        Informasi detail nilai bersifat rahasia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visualisasi Data -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">
            Statistik Penilaian
        </h2>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Bar Chart -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Distribusi Skor Rata-rata</h3>
                <canvas id="barChart"></canvas>
                <p class="text-sm text-gray-600 mt-4">
                    Grafik ini menunjukkan bahwa siswa berprestasi umumnya unggul di bidang akademik dan akhlak
                </p>
            </div>
            
            <!-- Pie Chart -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Komposisi Bobot Kriteria</h3>
                <canvas id="pieChart"></canvas>
                <p class="text-sm text-gray-600 mt-4">
                    Persentase bobot setiap kriteria dalam penilaian keseluruhan
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Kontak & Dukungan -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">
            Hubungi Kami
        </h2>
        
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold mb-2">Telepon</h3>
                <p class="text-gray-600">(0231) xxx-xxxx</p>
            </div>
            
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-bold mb-2">Email</h3>
                <p class="text-gray-600">info@sditassunnah.sch.id</p>
            </div>
            
            <div class="text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-orange-600 text-2xl"></i>
                </div>
                <h3 class="font-bold mb-2">Alamat</h3>
                <p class="text-gray-600">Jl. Contoh No. 123, Cirebon</p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <p class="text-gray-600 mb-4">Jam Operasional: Senin-Jumat 07.00-15.00 WIB</p>
        </div>
        
        <!-- Form Kontak -->
        <div class="max-w-2xl mx-auto mt-8">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-4">Butuh Bantuan?</h3>
                <form id="contactForm" class="space-y-4">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap
                            </label>
                            <input type="text" name="nama" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. WhatsApp
                            </label>
                            <input type="tel" name="whatsapp" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pesan/Pertanyaan
                        </label>
                        <textarea name="pesan" rows="4" class="w-full px-3 py-2 border rounded-lg" required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<!-- Chart.js untuk visualisasi -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simulator Nilai
    const simulatorInputs = document.querySelectorAll('.simulator-input');
    
    simulatorInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Update display value
            this.nextElementSibling.textContent = this.value;
            
            // Calculate total score
            calculateTotalScore();
        });
    });
    
    function calculateTotalScore() {
        let totalScore = 0;
        simulatorInputs.forEach(input => {
            const nilai = parseFloat(input.value);
            const bobot = parseFloat(input.dataset.bobot);
            totalScore += nilai * bobot;
        });
        document.getElementById('total-score').textContent = totalScore.toFixed(2);
    }
    
    // Initial calculation
    calculateTotalScore();
    
    // Accordion functionality
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });
    });
    
    // FAQ functionality
    const faqHeaders = document.querySelectorAll('.faq-header');
    faqHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('fa-plus');
            icon.classList.toggle('fa-minus');
        });
    });
    
    // Search Form
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("public.cari-anak") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                displaySearchResult(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
    }
    
    function displaySearchResult(data) {
        const resultDiv = document.getElementById('searchResult');
        
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Data ${data.siswa.nama}</h3>
                    <div class="space-y-2">
                        <p><strong>NIS:</strong> ${data.siswa.nis}</p>
                        <p><strong>Kelas:</strong> ${data.siswa.kelas}</p>
                        <p><strong>Peringkat Kelas:</strong> ${data.peringkat_kelas} dari ${data.total_kelas} siswa</p>
                        <p><strong>Peringkat Sekolah:</strong> ${data.peringkat_sekolah} dari ${data.total_sekolah} siswa</p>
                    </div>
                    <div class="mt-4 p-4 bg-blue-50 rounded">
                        <h4 class="font-semibold mb-2">Breakdown Nilai:</h4>
                        <div class="space-y-1 text-sm">
                            ${data.breakdown.map(item => `
                                <div class="flex justify-between">
                                    <span>${item.kriteria}:</span>
                                    <span class="font-medium">${item.status}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-red-600">${data.message}</p>
                </div>
            `;
        }
        
        resultDiv.classList.remove('hidden');
    }
    
    // Bar Chart - Distribusi Skor
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($kriteria->pluck('nama_kriteria')) !!},
                datasets: [{
                    label: 'Rata-rata Skor',
                    data: {!! json_encode($statistik['rata_rata_per_kriteria'] ?? [3.2, 3.5, 3.8, 3.0, 3.3, 2.8]) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4
                    }
                }
            }
        });
    }
    
    // Pie Chart - Komposisi Bobot
    const pieCtx = document.getElementById('pieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($kriteria->pluck('nama_kriteria')) !!},
                datasets: [{
                    data: {!! json_encode($kriteria->pluck('bobot_roc')->map(fn($b) => $b * 100)) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(99, 102, 241, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.toFixed(1) + '%';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Contact Form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulasi pengiriman
            alert('Terima kasih! Pesan Anda telah diterima. Kami akan menghubungi Anda segera.');
            this.reset();
        });
    }
});
</script>
@endsection