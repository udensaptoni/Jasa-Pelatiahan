@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h2>Tentang Kami</h2>

    {{-- Tombol kembali ke dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <a href="{{ route('admin.about.create') }}" class="btn btn-primary mb-3">+ Tambah Tentang Kami</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($about)
        <h4>{{ $about->title }}</h4>
        <p>{{ $about->content }}</p>
        @if($about->image)
            <img src="{{ asset('storage/'.$about->image) }}" width="200">
        @endif
        <div class="mt-3">
            <a href="{{ route('admin.about.edit',$about->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('admin.about.destroy',$about->id) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Yakin ingin hapus data ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Hapus</button>
            </form>
        </div>
    @else
        <p class="text-muted">Belum ada data tentang kami.</p>
    @endif
</div>
@endsection
