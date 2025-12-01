@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Daftar Testimoni</h2>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">Kembali</a>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Tambah Testimoni</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Pesan</th>
            <th>Status</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($testimonials as $key => $t)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $t->nama }}</td>
            <td>{{ Str::limit($t->pesan, 50) }}</td>
            <td>
                @if($t->status)
                    <span class="badge bg-success">Disetujui</span>
                @else
                    <span class="badge bg-secondary">Pending</span>
                @endif
            </td>
            <td>
                @if($t->foto)
                    <img src="{{ asset('storage/'.$t->foto) }}" alt="" width="60">
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('admin.testimonials.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>

                @if(!$t->status)
                    <form action="{{ route('admin.testimonials.approve', $t->id) }}" method="POST" style="display:inline; margin-left:6px;">
                        @csrf
                        <input type="hidden" name="status" value="1">
                        <button class="btn btn-success btn-sm" onclick="return confirm('Setujui testimoni ini?')">Setujui</button>
                    </form>
                @else
                    <form action="{{ route('admin.testimonials.approve', $t->id) }}" method="POST" style="display:inline; margin-left:6px;">
                        @csrf
                        <input type="hidden" name="status" value="0">
                        <button class="btn btn-secondary btn-sm" onclick="return confirm('Batalkan persetujuan testimoni?')">Batalkan</button>
                    </form>
                @endif

                <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" style="display:inline; margin-left:6px;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>

{{ $testimonials->links() }}
@endsection
