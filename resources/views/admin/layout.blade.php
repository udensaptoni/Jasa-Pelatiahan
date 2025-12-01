<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - JPelatihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{ --brand: #0d6efd; --admin-dark: #0b2545; }
        .sidebar-link { color: rgba(255,255,255,0.95); }
        .sidebar { background: linear-gradient(180deg, var(--admin-dark), #14293d); min-height: 100vh; }
        .brand { color: #fff; font-weight:700; }
        .nav-icon { width:1.25rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark" style="background: var(--admin-dark);">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
      <button class="btn btn-dark me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand d-flex align-items-center brand me-3" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i> Admin - JPelatihan
      </a>
  <!-- removed public site links per admin preference -->
    </div>

    <div class="d-flex align-items-center">
      <!-- Logout Form -->
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mb-0 me-2">
          @csrf
          <button type="button" id="logout-btn" class="btn btn-danger">Logout</button>
      </form>
      <a class="btn btn-light btn-sm" href="{{ route('home') }}" target="_blank" title="Buka situs di tab baru"><i class="bi bi-box-arrow-up-right"></i></a>
    </div>
  </div>
</nav>

<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
  <div class="offcanvas-header">
    <div class="d-flex align-items-center justify-content-between w-100">
      <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Menu</h5>
    </div>
    <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <ul class="nav flex-column p-2">
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill me-2 nav-icon"></i> Dashboard</a></li>
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.articles.index') }}"><i class="bi bi-journal-text me-2 nav-icon"></i> Artikel</a></li>
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam me-2 nav-icon"></i> Produk</a></li>
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.about.index') }}"><i class="bi bi-info-circle me-2 nav-icon"></i> Tentang Kami</a></li>
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.registrations.index') }}"><i class="bi bi-person-lines-fill me-2 nav-icon"></i> Registrasi</a></li>
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.testimonials.index') }}"><i class="bi bi-chat-quote-fill me-2 nav-icon"></i> Testimoni</a></li>
      <li class="nav-item"><a class="nav-link sidebar-link d-flex align-items-center" href="{{ route('admin.payments.index') }}"><i class="bi bi-credit-card-2-front-fill me-2 nav-icon"></i> Payments</a></li>
    </ul>
  </div>
</div>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            @yield('content')
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('logout-btn')?.addEventListener('click', function(e) {
    Swal.fire({
      title: 'Yakin Logout?',
      text: "Anda akan keluar dari sistem.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Logout',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('logout-form').submit();
      }
    })
  });
</script>
@yield('scripts')
</body>
</html>
