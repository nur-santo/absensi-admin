{{-- resources/views/auth/admin-login.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e293b, #0f172a);
        }

        .login-card {
            width: 100%;
            max-width: 360px;
            background: #fff;
            border-radius: 12px;
            padding: 28px 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .25);
        }

        .login-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .form-control {
            font-size: .9rem;
            padding: .6rem .75rem;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 1rem;
        }

        .toggle-password:hover {
            color: #111827;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

    <form method="POST" action="{{ route('admin.login.submit') }}" class="login-card">
        @csrf

        <div class="text-center mb-3">
            <div class="login-title">Admin Login</div>
        </div>

        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

        <div class="password-wrapper mb-3">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>

            <i class="bi bi-eye toggle-password" id="togglePassword"></i>
        </div>

        <button class="btn btn-dark w-100">
            Login
        </button>
    </form>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Ganti icon
            toggle.classList.toggle('bi-eye');
            toggle.classList.toggle('bi-eye-slash');
        });
    </script>

</body>

</html>
