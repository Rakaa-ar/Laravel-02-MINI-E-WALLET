<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini E-Wallet</title>
    @vite(['resources/css/login.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="login-body">

    <div class="login-card">
        <h3 class="fw-bold text-white mb-1">Welcome Back!</h3>
        <p class="text-abu mb-4" style="font-size: 0.9rem;">Sign in to your account</p>

        @if ($errors->any())
            <div class="alert alert-danger bg-danger text-white p-2 rounded mb-3" style="font-size: 0.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger bg-danger text-white p-2 rounded mb-3" style="font-size: 0.85rem;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="input-group-custom">
                <i class="bi bi-person"></i>
                <input type="email" name="email" class="login-input" placeholder="Email or Phone" required>
            </div>

            <div class="input-group-custom mb-2">
                <i class="bi bi-envelope"></i>
                <input type="password" name="password" class="login-input" placeholder="Password" required>
                <i style="cursor: pointer;"></i>
            </div>

            <div class="text-end mb-4">
                <a href="#" class="text-decoration-none text-purple" style="font-size: 0.85rem;">Forgot
                    Password?</a>
            </div>

            <button type="submit" class="btn btn-purple w-100 mb-3">
                Login
            </button>
        </form>

        <div class="divider">or continue with</div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="{{ route('google.login') }}" class="social-btn text-decoration-none">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="20">
            </a>
            <a href="#" class="social-btn text-decoration-none text-white fs-5">
                <i class="bi bi-apple"></i>
            </a>
            <a href="#" class="social-btn text-decoration-none text-white fs-5">
                <i class="bi bi-github"></i>
            </a>
        </div>

        <div class="text-center text-abu" style="font-size: 0.9rem;">
            Don't have an account? <a href="/register" class="text-decoration-none text-purple fw-semibold">Sign up</a>
        </div>
    </div>

</body>

</html>
