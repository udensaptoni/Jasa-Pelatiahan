<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Registrasi Peserta</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Laporan Registrasi Peserta</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Catatan</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $i => $reg)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $reg->product->title ?? '-' }}</td>
                    <td>{{ $reg->nama }}</td>
                    <td>{{ $reg->email }}</td>
                    <td>{{ $reg->telepon }}</td>
                    <td>{{ $reg->catatan }}</td>
                    <td>{{ $reg->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
