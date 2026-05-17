<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LensRent</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900i&family=dm-sans:300,400,500&display=swap" rel="stylesheet" />

    <style>
        :root {
            --black:   #0a0a0a;
            --charcoal:#111111;
            --border:  #2a2a2a;
            --gold:    #c9a84c;
            --gold-lt: #e8c97a;
            --cream:   #f5f0e8;
            --muted:   #777777;
            --white:   #ffffff;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--black);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* grain */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.5;
        }

        /* glow backdrop */
        body::after {
            content: '';
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 600px; height: 600px;
            background: radial-gradient(ellipse, rgba(201,168,76,0.06) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        /* back button fixed top-left */
        .back-btn {
            position: fixed;
            top: 1.5rem; left: 1.5rem;
            z-index: 10;
            display: inline-flex; align-items: center; gap: 0.5rem;
            color: var(--muted); text-decoration: none;
            font-size: 0.75rem; letter-spacing: 0.12em; text-transform: uppercase;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            background: rgba(17,17,17,0.8);
            backdrop-filter: blur(8px);
            transition: all 0.3s;
        }
        .back-btn:hover {
            color: var(--gold);
            border-color: var(--gold);
        }
        .back-btn svg {
            transition: transform 0.3s;
        }
        .back-btn:hover svg {
            transform: translateX(-3px);
        }

        .card {
            position: relative; z-index: 1;
            background: var(--charcoal);
            border: 1px solid var(--border);
            width: 100%; max-width: 420px;
            padding: 3rem 2.5rem;
            animation: fadeUp 0.7s 0.1s both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* top gold bar */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: var(--gold);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem; font-weight: 700;
            color: var(--white); text-align: center;
            margin-bottom: 2rem;
            display: block; text-decoration: none;
        }
        .logo span { color: var(--gold); }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem; font-weight: 700;
            color: var(--white); text-align: center;
            line-height: 1.1; margin-bottom: 0.5rem;
        }

        .card-sub {
            text-align: center; font-size: 0.83rem;
            color: var(--muted); margin-bottom: 2.5rem;
            letter-spacing: 0.03em;
        }

        .field { margin-bottom: 1.5rem; }

        label {
            display: block;
            font-size: 0.72rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 0.6rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: var(--black);
            border: 1px solid var(--border);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; font-weight: 300;
            padding: 0.8rem 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--gold);
        }

        input::placeholder { color: #444; }

        .error-msg {
            font-size: 0.75rem; color: #e57373;
            margin-top: 0.4rem;
        }

        .row {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .remember {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.78rem; color: var(--muted);
            cursor: pointer;
        }

        input[type="checkbox"] {
            appearance: none; -webkit-appearance: none;
            width: 14px; height: 14px;
            border: 1px solid var(--border);
            background: var(--black);
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: border-color 0.3s;
        }

        input[type="checkbox"]:checked {
            background: var(--gold);
            border-color: var(--gold);
        }

        input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 2px; left: 4px;
            width: 4px; height: 7px;
            border: 1.5px solid var(--black);
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        .forgot {
            font-size: 0.75rem; color: var(--gold);
            text-decoration: none; letter-spacing: 0.05em;
            transition: color 0.3s;
        }
        .forgot:hover { color: var(--gold-lt); }

        .btn-submit {
            width: 100%;
            background: var(--gold); color: var(--black);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem; font-weight: 500;
            letter-spacing: 0.12em; text-transform: uppercase;
            padding: 0.9rem;
            border: none; cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit:hover { background: var(--gold-lt); transform: translateY(-1px); }

        .register-row {
            text-align: center;
            font-size: 0.8rem; color: var(--muted);
            margin-top: 1.75rem;
        }

        .register-row a {
            color: var(--gold); text-decoration: none;
            font-weight: 500; transition: color 0.3s;
        }
        .register-row a:hover { color: var(--gold-lt); }

        .divider {
            display: flex; align-items: center; gap: 1rem;
            margin: 1.75rem 0;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }
        .divider span {
            font-size: 0.68rem; letter-spacing: 0.15em;
            text-transform: uppercase; color: #444;
        }
    </style>
</head>

<body>

    <a href="{{ url('/') }}" class="back-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="card">

        <a href="{{ url('/') }}" class="logo">Lens<span>Rent</span></a>

        <h1 class="card-title">Selamat Datang</h1>
        <p class="card-sub">Masuk untuk melanjutkan penyewaan</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="row">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    Ingat saya
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">Masuk</button>

            <div class="divider"><span>atau</span></div>

            <p class="register-row">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </p>

        </form>

    </div>

</body>
</html>