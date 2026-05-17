<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User Baru — LensRent</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900i&family=dm-sans:300,400,500,700&display=swap" rel="stylesheet" />

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
            --danger:  #e57373;
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

        /* grain background */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.5;
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

        .card {
            position: relative; z-index: 1;
            background: var(--charcoal);
            border: 1px solid var(--border);
            width: 100%; max-width: 460px;
            padding: 3rem 2.5rem;
            animation: fadeUp 0.7s 0.1s both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--gold);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem; font-weight: 700;
            color: var(--white); text-align: center;
            margin-bottom: 0.5rem;
        }

        .card-sub {
            text-align: center; font-size: 0.83rem;
            color: var(--muted); margin-bottom: 2.5rem;
        }

        .field { margin-bottom: 1.5rem; }

        label {
            display: block;
            font-size: 0.72rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 0.6rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: var(--black);
            border: 1px solid var(--border);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; font-weight: 300;
            padding: 0.85rem 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus { border-color: var(--gold); }

        .error-msg {
            font-size: 0.75rem; color: var(--danger);
            margin-top: 0.4rem;
        }

        .btn-submit {
            width: 100%;
            background: var(--gold); color: var(--black);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem; font-weight: 500;
            letter-spacing: 0.12em; text-transform: uppercase;
            padding: 0.95rem;
            border: none; cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-submit:hover { background: var(--gold-lt); transform: translateY(-1px); }
    </style>
</head>
<body>

    <!-- Tombol Kembali ke Halaman Index User Admin -->
    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline; margin-right:4px; vertical-align:middle;">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="card">
        <h1 class="card-title">Tambah User</h1>
        <p class="card-sub">Daftarkan akun pengguna baru ke dalam database sistem.</p>

        <!-- Form Mengarah ke Controller Store Method -->
        <form method="POST" action="{{ route('crud.store') }}">
            @csrf

            <!-- Kolom 1: Name -->
            <div class="field">
                <label for="name">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="Contoh: Muhammad Rian" 
                    required 
                    autofocus
                >
                @error('name')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kolom 2: Email -->
            <div class="field">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="rian@example.com" 
                    required
                >
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kolom 3: Nomor Telephone -->
            <div class="field">

                <label for="no_telp">
                    Nomor Telephone
                </label>

                <input
                    type="text"
                    id="no_telp"
                    name="no_telp"
                    value="{{ old('no_telp') }}"
                    placeholder="08123456789"
                    required
                >

                @error('no_telp')

                    <p class="error-msg">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <!-- Kolom 4: Alamat -->
            <div class="field">

                <label for="alamat">
                    Alamat
                </label>

                <input
                    type="text"
                    id="alamat"
                    name="alamat"
                    value="{{ old('alamat') }}"
                    placeholder="Jl. Contoh No. 123"
                    required
                >

                @error('alamat')

                    <p class="error-msg">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <!-- Kolom 3: Password -->
            <div class="field">
                <label for="password">Kata Sandi (Minimal 8 Karakter)</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required
                >
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Eksekusi Submit Data -->
            <button type="submit" class="btn-submit">
                Simpan ke Database
            </button>
        </form>
    </div>

</body>
</html>