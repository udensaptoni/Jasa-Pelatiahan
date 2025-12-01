<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jasa Pelatihan & Sertifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }

        /* Navbar */
        .navbar { transition: background 0.3s, padding 0.3s; }
        .navbar.scrolled { background: #0d6efd !important; padding: 8px 0; }
        .nav-link.active { color: #ffc107 !important; font-weight: 600; }

        /* Hero */
        .hero { 
            background: linear-gradient(to right, #0d6efd, #00bcd4);
            color: white;
            padding: 120px 0;
            overflow: hidden;
        }
        .hero h1 { font-weight: 700; font-size: 2.8rem; animation: fadeInDown 1s ease; }
        .hero p { font-size: 1.1rem; animation: fadeInUp 1s ease; }
        .hero .btn { animation: zoomIn 1.2s ease; }

        /* Section title */
        .section-title { 
            font-weight: bold; 
            margin-bottom: 40px; 
            position: relative; 
            opacity: 0;
            transform: translateY(30px);
            transition: all .6s ease;
        }
        .section-title.show {
            opacity: 1;
            transform: translateY(0);
        }
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: #0d6efd;
            margin: 12px auto 0;
        }

        /* Cards */
        .card { 
            border: none; 
            transition: transform 0.3s, box-shadow 0.3s; 
            opacity: 0; 
            transform: translateY(30px);
        }
        .card.show {
            opacity: 1;
            transform: translateY(0);
            transition: all .6s ease;
        }
        .card:hover { 
            transform: translateY(-8px) scale(1.02); 
            box-shadow: 0 10px 25px rgba(0,0,0,0.15); 
        }

        /* Tentang Kami */
        #tentang .row { align-items: center; }
        #tentang img { border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        /* Footer */
        footer { background: #0d6efd; color: white; }
        footer a { color: #ffc107; text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        /* Animations */
        @keyframes fadeInDown { from {opacity:0; transform:translateY(-30px);} to {opacity:1; transform:translateY(0);} }
        @keyframes fadeInUp { from {opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
        @keyframes zoomIn { from {opacity:0; transform:scale(0.8);} to {opacity:1; transform:scale(1);} }

        /* Offset anchor supaya tidak ketutup navbar */
        :root { --nav-height: 70px; }
        section { scroll-margin-top: calc(var(--nav-height) + 12px); }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-transparent py-3">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">JPelatihan</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="#produk">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="#artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
        <li class="nav-item"><a class="btn btn-warning ms-3" href="/login">Login Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero text-center d-flex align-items-center">
  <div class="container">
    <h1 class="mb-3">Hadirkan Kemudahan untuk <br><span class="text-warning">Pelatihan & Sertifikasi</span> Anda</h1>
    <p class="mb-4">Kami menyediakan program pelatihan dan sertifikasi terpercaya untuk mendukung karir dan bisnis Anda.</p>
    <a href="#produk" class="btn btn-light btn-lg shadow">Lihat Produk</a>
  </div>
</section>

<!-- Produk Section -->
<section id="produk" class="py-5">
  <div class="container">
    <h2 class="section-title text-center">Produk & Jasa Kami</h2>
    <div class="row">
      @foreach($products as $p)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          @if($p->image)
            <img src="{{ asset('storage/'.$p->image) }}" class="card-img-top" alt="{{ $p->name }}">
          @endif
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('produk.detail',$p->id) }}" class="text-decoration-none text-dark">
                {{ $p->name }}
              </a>
            </h5>
            <p class="card-text">{{ Str::limit($p->description, 100) }}</p>
            <p class="fw-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Artikel Section -->
<section id="artikel" class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center">Artikel Terbaru</h2>
    <div class="row">
      @foreach($articles as $a)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          @if($a->image)
            <img src="{{ asset('storage/'.$a->image) }}" class="card-img-top" alt="{{ $a->title }}">
          @endif
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('artikel.detail',$a->id) }}" class="text-decoration-none text-dark">
                {{ $a->title }}
              </a>
            </h5>
            <p class="card-text">{{ Str::limit($a->content, 100) }}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Tentang Kami Section -->
<section id="tentang" class="py-5">
  <div class="container">
    <h2 class="section-title text-center">Tentang Kami</h2>
    <div class="row mt-4">
      @if($about)
        <div class="col-md-6 text-start">
          <h4>{{ $about->title }}</h4>
          <p class="mt-3">{{ $about->content }}</p>
        </div>
        <div class="col-md-6 text-center">
          @if($about->image)
            <img src="{{ asset('storage/'.$about->image) }}" class="img-fluid mt-3" style="max-width:400px;">
          @endif
        </div>
      @else
        <div class="col-12 text-center">
          <p>Belum ada data tentang kami.</p>
        </div>
      @endif
    </div>
  </div>
</section>

<!-- Testimoni Section -->
<section id="testimoni" class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center">Apa Kata Mereka</h2>
    <div class="row">
      @if(!empty($testimonials) && $testimonials->count())
        @foreach($testimonials as $t)
        <div class="col-md-4 mb-4">
          <div class="card h-100 text-center p-3 border-0 shadow-sm">
            @if($t->foto)
              <img src="{{ asset('storage/'.$t->foto) }}" class="rounded-circle mx-auto mb-3" width="80" height="80" style="object-fit: cover;">
            @else
              <img src="{{ asset('images/default-user.png') }}" class="rounded-circle mx-auto mb-3" width="80" height="80" style="object-fit: cover;">
            @endif
            <h5>{{ $t->nama }}</h5>
            <p class="text-muted small">“{{ Str::limit($t->pesan, 120) }}”</p>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-12 text-center">
          <p>Belum ada testimoni yang ditampilkan.</p>
        </div>
      @endif
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="text-center py-4">
  <p class="mb-1">© {{ date('Y') }} Jasa Pelatihan & Sertifikasi</p>
  <p>
    <a href="#">Instagram</a> · 
    <a href="#">LinkedIn</a> · 
    <a href="#">Email</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Update CSS variable nav height dinamis
  function updateNavHeightVar(){
    const nav = document.querySelector('nav');
    if(!nav) return;
    document.documentElement.style.setProperty('--nav-height', nav.offsetHeight + 'px');
  }
  window.addEventListener('load', updateNavHeightVar);
  window.addEventListener('resize', updateNavHeightVar);

  // Navbar change color on scroll
  window.addEventListener("scroll", function() {
    const nav = document.querySelector("nav");
    if(!nav) return;
    nav.classList.toggle("scrolled", window.scrollY > 50);
  });

  // Highlight active nav link saat scroll
  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".nav-link");
  window.addEventListener("scroll", () => {
    let current = "";
    sections.forEach(section => {
      const sectionTop = section.offsetTop - 100;
      if (pageYOffset >= sectionTop) {
        current = section.getAttribute("id");
      }
    });
    navLinks.forEach(link => {
      link.classList.remove("active");
      if (link.getAttribute("href") === "#" + current) {
        link.classList.add("active");
      }
    });
  });

  // Smooth scroll & close navbar collapse
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if(!targetId || targetId === '#') return;
      const targetEl = document.querySelector(targetId);
      if(!targetEl) return;

      e.preventDefault();
      const nav = document.querySelector('nav');
      const navHeight = nav ? nav.offsetHeight : 70;
      const extraGap = 12;
      const top = targetEl.getBoundingClientRect().top + window.pageYOffset - navHeight - extraGap;

      window.scrollTo({ top: top, behavior: 'smooth' });

      const bsCollapse = document.querySelector('.navbar-collapse.show');
      if(bsCollapse){
        if (window.bootstrap && bootstrap.Collapse) {
          const inst = bootstrap.Collapse.getInstance(bsCollapse) || new bootstrap.Collapse(bsCollapse);
          inst.hide();
        } else {
          bsCollapse.classList.remove('show');
        }
      }
    });
  });

  // Animate on scroll (fade-in cards, titles)
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add('show');
      }
    });
  }, { threshold: 0.2 });

  document.querySelectorAll('.section-title, .card').forEach(el => observer.observe(el));
</script>
</body>
</html>
