@extends('admin.layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Tentang Kami</h2>

    <form action="{{ route('admin.about.update', $about->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $about->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="content" class="form-control" rows="4" required>{{ old('content', $about->content) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Sekarang</label><br>
            @if($about->image)
                <img src="{{ asset('storage/'.$about->image) }}" alt="About Us" class="img-thumbnail mb-2" style="max-width:200px;">
            @else
                <p><em>Belum ada gambar</em></p>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Ganti Gambar</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('admin.about.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
