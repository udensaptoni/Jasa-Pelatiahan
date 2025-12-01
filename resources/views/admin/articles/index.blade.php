@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Artikel</h3>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Kembali ke Dashboard</a>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">+ Tambah Artikel</a>
    </div>
</div>

@if(session('success')) 
    <div class="alert alert-success">{{ session('success') }}</div> 
@endif

<table class="table table-bordered">
    <tr>
        <th>Judul</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>
    @foreach($articles as $a)
    <tr>
        <td>{{ $a->title }}</td>
        <td>
            @if($a->image)
                <img src="{{ asset('storage/'.$a->image) }}" width="80">
            @endif
        </td>
        <td>
            <a href="{{ route('admin.articles.edit',$a->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('admin.articles.destroy',$a->id) }}" method="POST" style="display:inline-block;">
                @csrf 
                @method('DELETE')
                <button onclick="return confirm('Hapus artikel ini?')" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{ $articles->links() }}
@endsection
