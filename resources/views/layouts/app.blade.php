<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JPelatihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Theme tokens */
        :root{
            --brand-start: #007bff;
            --brand-end: #00c6ff;
            --muted: #6c757d;
            --footer-bg: linear-gradient(90deg,#00a8ff,#007bff);
            --login-yellow: #f6c23e;
        }
        .site-footer { background: var(--footer-bg); color: #ffffff; }
    .navbar-brand { letter-spacing: .5px; font-weight:700; color: #fff; }
    .nav-link.custom.active{ color: #fff; opacity:1; text-decoration:none; position:relative; }
    .nav-link.custom.active::after{ content:''; display:block; height:3px; width:36px; background:#fff; border-radius:2px; position:absolute; bottom:-10px; left:50%; transform:translateX(-50%);} 
        .card-elevated{ box-shadow: 0 6px 18px rgba(13,110,253,0.08); border: none; }
    .card-elevated{ box-shadow: 0 6px 18px rgba(13,110,253,0.08); border: none; transition: transform .15s ease, box-shadow .15s ease; }
    .card-elevated:hover{ transform: translateY(-6px); box-shadow: 0 20px 40px rgba(2,6,23,0.08); }
    .review-card{ border-radius: 12px; padding: 1rem; background: #fff; box-shadow: 0 8px 28px rgba(2,6,23,0.04); }
        .nav-btn-primary { background: linear-gradient(90deg,var(--brand-start),var(--brand-end)); color: #fff; border: none; }
    .top-hero { background: linear-gradient(90deg, var(--brand-start), var(--brand-end)); position:relative; z-index:1030; }
        .nav-link.custom { color: rgba(255,255,255,0.9); }
        .nav-link.custom:hover { color: #fff; opacity: 0.95; }
        .btn-login-yellow { background: var(--login-yellow); color: #111; font-weight:600; border-radius:8px; padding:8px 14px; }
    </style>
</head>
<body>
    <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark top-hero">
      <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-mortarboard-fill me-2" style="font-size:1.2rem"></i>
            <span>JPelatihan</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <form class="d-flex ms-auto me-3" role="search" action="{{ route('produk.index') }}" method="GET">
                <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Cari produk..." value="{{ request('q') }}" aria-label="Search">
                <button class="btn btn-sm btn-outline-light" type="submit">Cari</button>
            </form>
            <ul class="navbar-nav ms-2 align-items-center">
                <li class="nav-item"><a class="nav-link custom {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">Produk</a></li>
                <li class="nav-item"><a class="nav-link custom {{ request()->routeIs('artikel.*') ? 'active' : '' }}" href="{{ route('artikel.index') }}">Artikel</a></li>
                <li class="nav-item"><a class="nav-link custom {{ request()->routeIs('about.*') || request()->routeIs('about.show') ? 'active' : '' }}" href="{{ route('about.show') }}">Tentang Kami</a></li>
            </ul>
            <div class="d-flex ms-3">
                <a class="btn btn-login-yellow" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Login Admin</a>
            </div>
        </div>
        </div>
      </div>
    </nav>

    <!-- Content -->
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer text-center py-3">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 text-start">
                    <small>© {{ date('Y') }} JPelatihan</small>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <small class="text-white-50">Built with ❤ · <i class="bi bi-globe"></i> jpelatihan</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            })
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            })
        @endif
    </script>
</body>
</html>
