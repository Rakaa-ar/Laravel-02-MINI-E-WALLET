<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Password Baru - Mini E-Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 p-3" style="background-color: #0d0f17;">
    <div class="card card-auth p-4 shadow-lg" style="max-width: 400px; width: 100%;">
        <h3 class="fw-bold text-white text-center mb-2">Bikin Password Baru</h3>

        @if (session('info'))
            <div class="alert alert-info bg-info text-dark p-2 border-0 rounded-3 mb-3 small">{{ session('info') }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small text-secondary">Email</label>
                <input type="email" class="form-control form-control-dark bg-dark text-muted"
                    value="{{ $email }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label small text-secondary">Password Baru</label>
                <input type="password" name="password" class="form-control form-control-dark" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label small text-secondary">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control form-control-dark" required>
            </div>
            <button type="submit" class="btn btn-purple w-100 py-2">Simpan Password Baru</button>
        </form>
    </div>
</body>

</html>
