
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PKL Connect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: #f4f7fb;
            font-family: 'Inter', sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(20, 33, 61, .08);
        }

        .login-icon {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 18px;
        }

        .form-control {
            padding: 12px 14px;
            border-radius: 8px;
        }

        .btn-login {
            background: #2563eb;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #1d4ed8;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <h3 class="fw-bold mb-1">PKL Connect</h3>
            <p class="text-secondary mb-0">Sistem Informasi Absensi PKL</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                Email atau password tidak sesuai.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama</label>
<input
    id="name"
    type="text"
    name="name"
    value="{{ old('name') }}"
    class="form-control"
    placeholder="Masukkan nama depan"
    required
    autofocus
    autocomplete="username"
>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label small" for="remember_me">
                        Ingat saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-login w-100">
                Masuk <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <p class="text-center text-secondary small mt-4 mb-0">
            © {{ date('Y') }} PKL Connect
        </p>

    </div>
</div>

</body>
</html>
