<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap tetap dipakai --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#e5e5e5;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card{
            width:360px;
            background:linear-gradient(180deg,#071326,#030a17);
            border-radius:20px;
            padding:35px 28px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        .login-title{
            color:white;
            font-weight:600;
            font-size:24px;
            text-align:center;
            margin-bottom:25px;
        }

        .input-custom{
            background:#0c1b33;
            border:none;
            border-radius:12px;
            padding:14px;
            color:white;
            margin-bottom:15px;
        }

        .input-custom::placeholder{
            color:#8b97aa;
        }

        .input-custom:focus{
            outline:none;
            box-shadow:0 0 0 2px rgba(40,180,120,0.4);
            background:#0c1b33;
            color:white;
        }

        .login-btn{
            width:100%;
            padding:14px;
            border:none;
            border-radius:14px;
            background:linear-gradient(90deg,#36b37e,#2ecc71);
            color:white;
            font-weight:600;
            transition:0.2s;
        }

        .login-btn:hover{
            transform:scale(1.02);
            opacity:0.9;
        }
    </style>
</head>
<body>

<form method="POST" action="{{ route('admin.login.submit') }}" class="login-card">
    @csrf

    <div class="login-title">Absensi App</div>

    <input type="email"
           name="email"
           class="form-control input-custom"
           placeholder="Email"
           required>

    <input type="password"
           name="password"
           class="form-control input-custom"
           placeholder="Password"
           required>

    <button class="login-btn">Login</button>
</form>

</body>
</html>