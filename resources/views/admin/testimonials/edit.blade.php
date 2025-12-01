@extends('admin.layout')

@section('content')
<h2>Edit Testimoni</h2>
<form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $testimonial->nama }}" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $testimonial->email }}">
    </div>
    <div class="mb-3">
        <label>Pesan</label>
        <textarea name="pesan" class="form-control" rows="4" required>{{ $testimonial->pesan }}</textarea>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="0" {{ !$testimonial->status ? 'selected' : '' }}>Pending</option>
            <option value="1" {{ $testimonial->status ? 'selected' : '' }}>Disetujui</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Foto</label><br>
        @if($testimonial->foto)
            <img src="{{ asset('storage/'.$testimonial->foto) }}" width="100" class="mb-2"><br>
        @endif
        <input type="file" name="foto" class="form-control">
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary me-2">Kembali</a>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
