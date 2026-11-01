<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Mini E-Wallet</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3" style="background-color: #0d0f17;">

    <div class="card card-auth p-4 shadow-lg text-center" style="max-width: 400px;">
        <h3 class="fw-bold text-white mb-2">Lupa Password?</h3>
        <p class="text-secondary small mb-4">Masukkan email lu, ntar gua kirimin link buat reset passwordnya.</p>

        @if (session('info'))
            <div class="alert alert-info bg-info text-dark p-2 border-0 rounded-3 mb-3 small">
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4 text-start">
                <label class="form-label small text-secondary">Email Address</label>
                <input type="email" name="email" class="form-control form-control-dark" placeholder="nama@email.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-purple w-100 py-2">Kirim Link Reset</button>
        </form>

        <div class="mt-4 small">
            <a href="{{ route('login') }}" class="text-secondary text-decoration-none hover-white">
                &larr; Balik ke halaman Login
            </a>
        </div>
    </div>

</body>
</html>