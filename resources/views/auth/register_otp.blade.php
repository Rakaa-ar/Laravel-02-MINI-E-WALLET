<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Mini E-Wallet</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3" style="background-color: #0d0f17;">

    <div class="card card-auth p-4 shadow-lg text-center" style="max-width: 380px;">
        <h3 class="fw-bold text-white mb-1">Verifikasi OTP</h3>
        <p class="text-abu small mb-4">Masukkan 6 digit kode yang dikirim ke Gmail lu</p>

        @if (session('info'))
            <div class="alert alert-info bg-info text-dark p-2 border-0 rounded-3 mb-3 small">
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger bg-danger text-white p-2 border-0 rounded-3 mb-3 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/verify-otp') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="text" name="otp" class="form-control form-control-dark text-center fs-3 fw-bold letter-spacing-2" placeholder="123456" maxlength="6" required autofocus autocomplete="off">
            </div>
            <button type="submit" class="btn btn-purple w-100 py-2">Verifikasi & Masuk</button>
        </form>
    </div>

</body>
</html>