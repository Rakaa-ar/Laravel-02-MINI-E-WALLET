<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mini E-Wallet</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS via Vite -->
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3" style="background-color: #0d0f17;">

    <div class="card card-auth p-4 shadow-lg">
        <h3 class="fw-bold text-center text-white mb-1">Create Account</h3>
        <p class="text-secondary text-center small mb-4">Sign up to get started</p>

        @if ($errors->any())
            <div class="alert alert-danger bg-danger text-white p-2 border-0 rounded-3 mb-3 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label small text-secondary">Full Name</label>
                <input type="text" name="name" class="form-control form-control-dark" placeholder="Your name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label small text-secondary">Email</label>
                <input type="email" name="email" class="form-control form-control-dark" placeholder="name@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small text-secondary">Password</label>
                <input type="password" name="password" class="form-control form-control-dark" placeholder="Min. 6 characters" required>
            </div>

            <button type="submit" class="btn btn-purple w-100 mt-2 mb-3">Daftar & Kirim OTP</button>
        </form>

        <div class="text-center small text-secondary">
            Already have an account? <a href="{{ route('login') }}" class="text-purple text-decoration-none fw-semibold">Sign in</a>
        </div>
    </div>

</body>
</html>