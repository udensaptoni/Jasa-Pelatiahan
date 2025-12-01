@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Testimoni Peserta</h2>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form kirim testimoni --}}
        <div class="card card-elevated mb-5 p-4">
            <h4 class="mb-3">Bagikan Pengalamanmu</h4>
            <form action="{{ route('frontend.testimoni.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email (opsional)</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Pesan <span class="text-danger">*</span></label>
                    <textarea name="pesan" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Foto (opsional)</label>
                    <input type="file" name="foto" class="form-control">
                </div>
                <button type="submit" class="btn nav-btn-primary"><i class="bi bi-send me-1"></i> Kirim Testimoni</button>
            </form>
        </div>

        {{-- Daftar testimoni --}}
        <h4 class="text-center mb-4">Apa Kata Mereka?</h4>
        <div class="row">
            @forelse($testimonials as $t)
                <div class="col-md-4 mb-4">
                    <div class="card card-elevated border-0 p-3 text-center">
                        @if($t->foto)
                            <img src="{{ asset('storage/'.$t->foto) }}" class="rounded-circle mx-auto mb-3" width="80" height="80" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-user.png') }}" class="rounded-circle mx-auto mb-3" width="80" height="80" style="object-fit: cover;">
                        @endif
                        <h5>{{ $t->nama }}</h5>
                        <p class="text-muted small">“{{ $t->pesan }}”</p>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Belum ada testimoni yang ditampilkan.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $testimonials->links() }}
        </div>
    </div>
</section>
@endsection
