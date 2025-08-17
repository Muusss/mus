<!-- File: resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk | SPK Siswa Teladan - SDIT As Sunnah</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <style>
    :root{
      /* Tetap aksen oranye seperti desain kamu */
      --primary:#ff6b35;
      --primary-dark:#e55100;
      --ring: rgba(255,107,53,.16);
      --card: rgba(255,255,255,.96);
      --logo-size: 150px; 
    }

    *{box-sizing:border-box}
    body{
      font-family:'Figtree',sans-serif;
      min-height:100vh; display:flex; flex-direction:column; overflow-x:hidden;
      background: radial-gradient(1000px 520px at 15% -10%, #FF955533, transparent 70%),
                  radial-gradient(900px 500px at 120% 120%, #FF6B3544, transparent 70%),
                  linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Nav kanan atas */
    .nav-actions{position:absolute; top:18px; right:18px; display:flex; gap:10px; z-index:10}
    .nav-btn{
      padding:10px 18px; border-radius:999px; font-weight:600; color:#fff; text-decoration:none;
      border:1.8px solid #ffffff80; background:#ffffff14; backdrop-filter:blur(10px);
      display:inline-flex; align-items:center; gap:8px; transition:.25s ease;
    }
    .nav-btn:hover{ background:#fff; color:var(--primary); transform:translateY(-1px) }

    /* Wrapper */
    .wrap{flex:1; display:flex; align-items:center; justify-content:center; padding:28px}

    /* Card */
    .card-login{
      width:100%; max-width:560px; background:var(--card); backdrop-filter:blur(20px);
      border-radius:26px; padding:40px 36px; box-shadow:0 24px 54px rgba(0,0,0,.18);
      border-top:4px solid var(--primary); animation:fadeUp .6s ease-out;
    }
    @keyframes fadeUp{from{opacity:0; transform:translateY(18px)} to{opacity:1; transform:translateY(0)}}

    .logo-wrap{ display:flex; justify-content:center; margin-bottom:16px; }
    .logo{
    width: var(--logo-size);
    height: var(--logo-size);
    border-radius: 24px;
    padding: 16px;
    background:#fff;
    border:3px solid var(--primary);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    animation:glow 2s ease-in-out infinite alternate;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    }
    .logo img{ width:100%; height:100%; object-fit:contain; background:transparent }
    .logo-fallback{ font-size:50px; color:var(--primary) }
    @keyframes glow{from{box-shadow:0 0 10px -10px var(--primary)} to{box-shadow:0 0 20px 10px var(--primary)}}

    .title{
      text-align:center; font-weight:800; font-size:1.8rem; margin:10px 0 4px;
      background:linear-gradient(135deg,var(--primary),var(--primary-dark));
      -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .subtitle{ text-align:center; color:#6b7280; font-weight:600; margin-bottom:20px }

    /* Form field */
    .form-label{ font-weight:600; color:#374151 }
    .form-control{
      border-radius:14px; padding:12px 14px; border:1px solid #e5e7eb;
      transition:border-color .15s ease, box-shadow .15s ease;
    }
    .form-control:focus{
      border-color:var(--primary);
      box-shadow:0 0 0 .25rem var(--ring);
    }

    /* --- FIX: ikon mata berada rapi di dalam kolom --- */
    .password-wrap{ position: relative; }
    .password-wrap .form-control{ padding-right: 3rem; }  /* ruang untuk tombol */
    .toggle-pass{
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;
      border:0; background:transparent; color:#6c757d; padding:0; line-height:1; cursor:pointer;
    }
    .toggle-pass:hover{ color:#374151 }

    .btn-primary{
      background:var(--primary); border-color:var(--primary);
      border-radius:14px; padding:12px 16px; font-weight:800;
      box-shadow:0 10px 20px rgba(255,107,53,.2);
    }
    .btn-primary:hover{ background:var(--primary-dark); border-color:var(--primary-dark) }

    @media (max-width:768px){
      .card-login{ padding:32px 24px; border-radius:22px }
      :root{ --logo-size: 120px; }
      .nav-actions{ right:12px; top:12px }
    }

    .footer{ color:#fff; text-align:center; padding:14px; font-size:.925rem; opacity:.9; }
  </style>
</head>
<body>
  <!-- Nav kanan atas -->
  <div class="nav-actions">
    <a href="{{ url('/') }}" class="nav-btn"><i class="bi bi-house-door"></i> Beranda</a>
    <a href="{{ route('hasil.publik') }}" class="nav-btn"><i class="bi bi-trophy"></i> Hasil Peringkat</a>
  </div>

  <main class="wrap">
    <section class="card-login">
      <!-- Logo -->
      <div class="logo-wrap">
        <div class="logo">
            <img src="{{ asset('img/logo-yac.png') }}" alt="Logo YAC" class="logo-img">
        </div>
        </div>

      <h1 class="title">SPK Siswa Teladan</h1>
      <p class="subtitle">Masuk untuk melanjutkan</p>

      <!-- Error -->
      @if ($errors->any())
        <div class="alert alert-danger border-0">
          <div class="fw-semibold mb-1">Terjadi kesalahan:</div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('login') }}" class="mt-3">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                 class="form-control" placeholder="nama@sekolah.sch.id">
        </div>

        <div class="mb-3 password-wrap">
          <label for="password" class="form-label">Kata Sandi</label>
          <input id="password" type="password" name="password" required
                 class="form-control" placeholder="••••••••">
          <button type="button" class="toggle-pass" aria-label="Lihat/Sembunyikan sandi">
            <i class="bi bi-eye"></i>
          </button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
          </div>
          {{-- @if (Route::has('password.request'))
            <a class="link-offset-1" href="{{ route('password.request') }}">Lupa sandi?</a>
          @endif --}}
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
      </form>
    </section>
  </main>

  <div class="footer">
    &copy; {{ date('Y') }} SDIT As Sunnah Cirebon • Laravel {{ Illuminate\Foundation\Application::VERSION }}
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle show/hide password
    (() => {
      const btn = document.querySelector('.toggle-pass');
      const input = document.getElementById('password');
      if(!btn || !input) return;
      btn.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
      });
    })();
  </script>
</body>
</html>
