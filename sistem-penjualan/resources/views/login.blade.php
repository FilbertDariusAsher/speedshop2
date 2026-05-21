<!DOCTYPE html>
<html>

<head>
    <title>Login - SPEEDSHOP 2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 20%, rgba(15, 58, 128, 0.75), transparent 55%),
                radial-gradient(circle at 80% 10%, rgba(84, 61, 255, 0.65), transparent 55%),
                linear-gradient(135deg, #0b1f4f, #0f2a6b);
            color: #f8fafc;
        }

        .login-card {
            border-radius: 1.25rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .login-card .card-body {
            padding: 2.5rem 2.25rem;
        }

        .login-brand {
            font-weight: 700;
            letter-spacing: 0.14em;
            color: #000000;
            text-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        .login-title {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .form-control {
            border-radius: 0.75rem;
            border: 1px solid rgba(15, 20, 45, 0.35);
            background: rgba(255, 255, 255, 0.92);
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            border-radius: 0.75rem;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.25);
        }


        .register-link {
            color: #ffffff !important;
            text-decoration: none;
        }

        .register-link:hover {
            color: #ffffff !important;
        }

        @media (max-width: 576px) {
            .login-card .card-body {
                padding: 2rem 1.75rem;
            }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card shadow-lg">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h1 class="login-title mt-3">Masuk</h1>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger rounded-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.perform') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="Masukkan Email" required autofocus>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input id="loginPassword" type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan Password" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-check form-switch mt-2 text-white-75">
                                    <input class="form-check-input" type="checkbox" id="loginShowPassword" onchange="togglePassword('loginPassword')">
                                    <label class="form-check-label" for="loginShowPassword">Tampilkan password</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                Masuk sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-white-50 mt-3 small">&copy; {{ date('Y') }} SPEEDSHOP 2</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>