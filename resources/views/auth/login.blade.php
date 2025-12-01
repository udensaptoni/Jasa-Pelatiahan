<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Jasa Pelatihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
        }
        .login-box {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="login-box">
    <h3 class="text-center mb-4">Login Admin</h3>
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="admin@jpelatihan.com">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    
    <div class="mt-3 text-center">
        <a href="{{ route('home') }}" class="btn btn-secondary w-100">← Kembali ke Halaman Utama</a>
    </div>
</div>
</body>
</html>
