<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SDIT As Sunnah Cirebon')</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Bootstrap dulu -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tailwind tanpa preflight & container (biar gak tabrakan sama Bootstrap) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      corePlugins: { preflight: false, container: false }
    }
  </script>

  <style>
    body{ font-family:'Inter',sans-serif; }

    /* NAVBAR: kontras dan di atas konten lain */
    .navbar-custom{
      background: linear-gradient(135deg,#ff6b35 0%,#e55100 100%);
      box-shadow: 0 2px 8px rgba(0,0,0,.15);
      z-index: 1050;
    }
    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link,
    .navbar-custom .navbar-toggler{ color:#fff !important; }
    .navbar-custom .nav-link{ opacity:.9; }
    .navbar-custom .nav-link.active,
    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link:focus{ color:#fff !important; opacity:1; }
    .navbar-custom .navbar-toggler{ border-color: rgba(255,255,255,.4); }
    .navbar-custom .navbar-toggler-icon{ filter: invert(1) brightness(1.2); }

    /* Logo aman, tidak jadi spanduk */
    :root{ --brand-h:44px; }
    @media (max-width:576px){ :root{ --brand-h:36px; } }
    .navbar-brand .brand-logo{
      height: var(--brand-h); width:auto; display:block; object-fit:contain;
      image-rendering: -webkit-optimize-contrast;
    }

    /* Tombol login di navbar */
    .navbar-custom .btn-light{
      background:#fff; color:#e55100; border:0;
      box-shadow: 0 2px 6px rgba(0,0,0,.1);
    }
    .navbar-custom .btn-light:hover{ opacity:.95; }

    /* FOOTER: teks & link jelas di latar gelap */
    footer.site-footer{ background:#0f172a; color:#e5e7eb; }
    .site-footer .text-muted{ color:rgba(255,255,255,.75) !important; }
    .site-footer a{ color:rgba(255,255,255,.9) !important; text-decoration:none; }
    .site-footer a:hover{ color:#fff !important; text-decoration:underline; text-underline-offset:2px; }
    .site-footer hr{ border-color:rgba(255,255,255,.2); }

    /* Kartu hover util */
    .card-hover{ transition: all .3s; }
    .card-hover:hover{ transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,.1); }
  </style>

  @yield('styles')
</head>
<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img
          src="{{ asset('img/logo-yac.png') }}"
          srcset="{{ asset('img/logo-yac.png') }} 1x, {{ asset('img/logo-yac@2x.png') }} 2x"
          alt="Logo YAC"
          class="brand-logo"
          width="176" height="176"
          fetchpriority="high"
        >
        <span class="fw-bold ms-2">SDIT As Sunnah</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('public.informasi') ? 'active' : '' }}" href="{{ route('public.informasi') }}">Informasi SPK</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('hasil.publik') ? 'active' : '' }}" href="{{ route('hasil.publik') }}">Hasil Peringkat</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#kontak">Kontak</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-light btn-sm px-3" href="{{ route('login') }}">
              <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main>@yield('content')</main>

  <!-- Footer -->
  <footer class="site-footer text-white py-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5 class="mb-3">SDIT As Sunnah Cirebon</h5>
          <p class="text-muted">Membentuk generasi Qur'ani yang berakhlak mulia dan berprestasi.</p>
          <div class="mt-3">
            <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
            <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
            <a href="#" class="me-3"><i class="fab fa-youtube fa-lg"></i></a>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <h5 class="mb-3">Link Cepat</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="{{ route('welcome') }}">Beranda</a></li>
            <li class="mb-2"><a href="{{ route('public.informasi') }}">Informasi SPK</a></li>
            <li class="mb-2"><a href="{{ route('hasil.publik') }}">Hasil Peringkat</a></li>
            <li class="mb-2"><a href="{{ route('login') }}">Login Admin</a></li>
          </ul>
        </div>

        <div class="col-md-4 mb-4" id="kontak">
          <h5 class="mb-3">Kontak Kami</h5>
          <ul class="list-unstyled text-muted">
            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Jl. Contoh No. 123, Cirebon</li>
            <li class="mb-2"><i class="fas fa-phone me-2"></i>(0231) xxx-xxxx</li>
            <li class="mb-2"><i class="fas fa-envelope me-2"></i>info@sditassunnah.sch.id</li>
            <li class="mb-2"><i class="fas fa-clock me-2"></i>Senin - Jumat: 07.00 - 15.00 WIB</li>
          </ul>
        </div>
      </div>

      <hr class="border-secondary my-4">

      <div class="text-center" style="color:rgba(255,255,255,.75)">
        &copy; {{ date('Y') }} SDIT As Sunnah Cirebon. All rights reserved.
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @yield('scripts')
</body>
</html>
