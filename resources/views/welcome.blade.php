<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LensRent — Sewa Kamera DSLR</title>
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
        html { scroll-behavior: smooth; }

        body {
            background: var(--black);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 9999; opacity: 0.5;
        }

        /* NAV */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 2.5rem;
            border-bottom: 1px solid var(--border);
            background: rgba(10,10,10,0.9);
            backdrop-filter: blur(12px);
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem; font-weight: 700; color: var(--white);
        }
        .nav-logo span { color: var(--gold); }

        .nav-auth { display: flex; gap: 0.75rem; align-items: center; }

        .btn-ghost {
            color: var(--cream); text-decoration: none;
            font-size: 0.78rem; letter-spacing: 0.1em; text-transform: uppercase;
            padding: 0.55rem 1.2rem; border: 1px solid var(--border);
            transition: all 0.3s;
        }
        .btn-ghost:hover { border-color: var(--gold); color: var(--gold); }

        .btn-gold {
            background: var(--gold); color: var(--black); text-decoration: none;
            font-size: 0.78rem; letter-spacing: 0.1em; text-transform: uppercase;
            padding: 0.55rem 1.2rem; font-weight: 500; transition: all 0.3s;
        }
        .btn-gold:hover { background: var(--gold-lt); }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: grid; grid-template-columns: 1fr 1fr;
            padding-top: 70px;
        }

        .hero-left {
            display: flex; flex-direction: column; justify-content: center;
            padding: 5rem 3rem;
        }

        .hero-eyebrow {
            font-size: 0.68rem; letter-spacing: 0.28em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 1.75rem;
            display: flex; align-items: center; gap: 0.75rem;
            animation: fadeUp 0.7s 0.1s both;
        }
        .hero-eyebrow::before {
            content: ''; display: inline-block;
            width: 1.75rem; height: 1px; background: var(--gold);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 4.5vw, 5rem);
            font-weight: 900; line-height: 1.05; color: var(--white);
            margin-bottom: 1.25rem;
            animation: fadeUp 0.7s 0.2s both;
        }
        h1 em { font-style: italic; color: var(--gold); }

        .hero-sub {
            font-size: 0.95rem; line-height: 1.75; color: var(--muted);
            max-width: 400px; margin-bottom: 2.75rem;
            animation: fadeUp 0.7s 0.3s both;
        }

        .hero-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--gold); color: var(--black); text-decoration: none;
            font-size: 0.82rem; letter-spacing: 0.1em; text-transform: uppercase;
            padding: 0.9rem 2.2rem; font-weight: 500; transition: all 0.3s;
            align-self: flex-start;
            animation: fadeUp 0.7s 0.4s both;
        }
        .hero-btn:hover { background: var(--gold-lt); transform: translateY(-1px); }

        .hero-right {
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .hero-right::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 55% 60% at 50% 50%, rgba(201,168,76,0.07) 0%, transparent 70%);
        }

        .cam-wrap {
            position: relative; width: 420px; height: 420px;
            display: flex; align-items: center; justify-content: center;
            animation: fadeUp 0.9s 0.3s both;
        }

        .cam-ring {
            position: absolute; border-radius: 50%;
            border: 1px solid rgba(201,168,76,0.12);
        }
        .cam-ring:nth-child(1) { inset: 0; animation: spin 28s linear infinite; }
        .cam-ring:nth-child(2) { inset: 35px; animation: spin 20s linear infinite reverse; border-color: rgba(201,168,76,0.06); }

        @keyframes spin { to { transform: rotate(360deg); } }

        .cam-svg {
            position: relative; z-index: 1;
            filter: drop-shadow(0 0 35px rgba(201,168,76,0.18));
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-12px); }
        }

        /* GEAR */
        .section { max-width: 1100px; margin: 0 auto; padding: 5rem 2.5rem; }

        .sec-eyebrow {
            font-size: 0.68rem; letter-spacing: 0.28em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 0.75rem;
            display: flex; align-items: center; gap: 0.6rem;
        }
        .sec-eyebrow::before {
            content: ''; display: inline-block;
            width: 1.5rem; height: 1px; background: var(--gold);
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 2.5vw, 2.6rem);
            font-weight: 700; color: var(--white); line-height: 1.15;
            margin-bottom: 3rem;
        }

        .gear-list {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1px; background: var(--border);
        }

        .gear-item {
            background: var(--charcoal);
            padding: 2rem 2rem 2.25rem;
            position: relative; overflow: hidden;
            transition: background 0.3s;
        }
        .gear-item:hover { background: #161616; }

        .gear-item::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 1px; background: var(--gold);
            transform: scaleX(0); transition: transform 0.4s;
            transform-origin: left;
        }
        .gear-item:hover::after { transform: scaleX(1); }

        .gear-num {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem; font-weight: 900;
            color: rgba(255,255,255,0.03);
            position: absolute; top: 1rem; right: 1.25rem;
            line-height: 1; user-select: none;
        }

        .gear-brand {
            font-size: 0.68rem; letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 0.5rem;
        }

        .gear-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem; font-weight: 700; color: var(--white);
            margin-bottom: 0.75rem;
        }

        .gear-desc {
            font-size: 0.82rem; line-height: 1.65; color: var(--muted);
        }

        /* STEPS */
        .steps-wrap {
            background: var(--charcoal);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .steps {
            display: grid; grid-template-columns: repeat(4, 1fr);
            max-width: 1100px; margin: 0 auto;
        }

        .step {
            padding: 3rem 2rem;
            border-right: 1px solid var(--border);
        }
        .step:last-child { border-right: none; }

        .step-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem; font-weight: 900;
            color: rgba(201,168,76,0.18);
            line-height: 1; margin-bottom: 1.25rem;
        }

        .step-title {
            font-size: 0.9rem; font-weight: 500; color: var(--white);
            margin-bottom: 0.6rem;
        }

        .step-desc {
            font-size: 0.8rem; line-height: 1.7; color: var(--muted);
        }

        /* FOOTER */
        footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 2rem 2.5rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem; font-weight: 700; color: var(--white);
        }
        .footer-logo span { color: var(--gold); }

        .footer-copy { font-size: 0.7rem; color: var(--muted); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--black); }
        ::-webkit-scrollbar-thumb { background: var(--gold); }
    </style>
</head>
<body>

    <nav>
        <div class="nav-logo">Lens<span>Rent</span></div>
        <div class="nav-auth">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-ghost">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-gold">Daftar</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <div class="hero">
        <div class="hero-left">
            <div class="hero-eyebrow">Penyewaan Kamera Profesional</div>
            <h1>Abadikan <em>Momen</em><br>Tanpa Batas</h1>
            <p class="hero-sub">
                Sewa kamera DSLR & mirrorless premium untuk wedding, event, liputan, dan proyek kreatifmu. Gear terbaik, siap pakai kapan saja.
            </p>
            <a href="{{ route('register') }}" class="hero-btn">
                Mulai Sewa
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="hero-right">
            <div class="cam-wrap">
                <div class="cam-ring"></div>
                <div class="cam-ring"></div>
                <svg class="cam-svg" width="270" height="215" viewBox="0 0 280 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="60" width="240" height="150" rx="10" fill="#1a1a1a" stroke="#c9a84c" stroke-width="1.5"/>
                    <rect x="180" y="30" width="70" height="50" rx="6" fill="#1a1a1a" stroke="#c9a84c" stroke-width="1.5"/>
                    <rect x="90" y="38" width="80" height="35" rx="5" fill="#141414" stroke="#c9a84c" stroke-width="1"/>
                    <circle cx="205" cy="44" r="8" fill="#c9a84c" opacity="0.9"/>
                    <circle cx="205" cy="44" r="5" fill="#a88838"/>
                    <circle cx="120" cy="150" r="70" fill="#141414" stroke="#c9a84c" stroke-width="2"/>
                    <circle cx="120" cy="150" r="60" fill="#0f0f0f" stroke="#333" stroke-width="1"/>
                    <circle cx="120" cy="150" r="50" fill="none" stroke="#c9a84c" stroke-width="0.5" opacity="0.35"/>
                    <circle cx="120" cy="150" r="38" fill="none" stroke="#c9a84c" stroke-width="0.5" opacity="0.22"/>
                    <circle cx="120" cy="150" r="26" fill="none" stroke="#c9a84c" stroke-width="0.5" opacity="0.14"/>
                    <circle cx="120" cy="150" r="20" fill="url(#lg)"/>
                    <circle cx="111" cy="141" r="4" fill="white" opacity="0.1"/>
                    <circle cx="220" cy="80" r="16" fill="#222" stroke="#c9a84c" stroke-width="1"/>
                    <text x="220" y="85" text-anchor="middle" fill="#c9a84c" font-size="10" font-family="serif">M</text>
                    <rect x="180" y="120" width="30" height="10" rx="5" fill="#222" stroke="#555" stroke-width="0.5"/>
                    <rect x="180" y="120" width="14" height="10" rx="5" fill="#c9a84c" opacity="0.8"/>
                    <rect x="14" y="75" width="10" height="20" rx="3" fill="#222" stroke="#c9a84c" stroke-width="1"/>
                    <rect x="256" y="75" width="10" height="20" rx="3" fill="#222" stroke="#c9a84c" stroke-width="1"/>
                    <text x="175" y="175" fill="#c9a84c" font-size="7" font-family="monospace" opacity="0.5">24.1 MP · 4K</text>
                    <defs>
                        <radialGradient id="lg" cx="40%" cy="40%">
                            <stop offset="0%" stop-color="#1a3a6a" stop-opacity="0.8"/>
                            <stop offset="100%" stop-color="#0a0f18"/>
                        </radialGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="sec-eyebrow">Koleksi Gear</div>
        <h2>Pilih Kamera<br>Yang Tepat</h2>
        <div class="gear-list">
            <div class="gear-item">
                <div class="gear-num">01</div>
                <div class="gear-brand">Canon · Full Frame</div>
                <div class="gear-name">Canon EOS R5</div>
                <p class="gear-desc">45MP full-frame, video 8K RAW, IBIS 8-stop. Pilihan utama fotografer profesional wedding & komersial.</p>
            </div>
            <div class="gear-item">
                <div class="gear-num">02</div>
                <div class="gear-brand">Nikon · Full Frame</div>
                <div class="gear-name">Nikon Z6 III</div>
                <p class="gear-desc">24.5MP partial stacked CMOS, video 6K ProRes, autofokus tercepat di kelasnya. Serbaguna untuk foto & video.</p>
            </div>
            <div class="gear-item">
                <div class="gear-num">03</div>
                <div class="gear-brand">Sony · Full Frame</div>
                <div class="gear-name">Sony A7 IV</div>
                <p class="gear-desc">33MP BSI-CMOS, video 4K 60fps 10-bit, real-time eye tracking. Ideal untuk potret & dokumentasi event.</p>
            </div>
            <div class="gear-item">
                <div class="gear-num">04</div>
                <div class="gear-brand">Canon · APS-C</div>
                <div class="gear-name">Canon EOS 90D</div>
                <p class="gear-desc">32.5MP APS-C DSLR klasik, buffer besar, dual-pixel AF. Sempurna untuk sport, wildlife & pemula serius.</p>
            </div>
            <div class="gear-item">
                <div class="gear-num">05</div>
                <div class="gear-brand">Fujifilm · APS-C</div>
                <div class="gear-name">Fujifilm X-T5</div>
                <p class="gear-desc">40MP dengan film simulation legendaris. Pilihan street photographer & traveler yang ingin hasil estetik.</p>
            </div>
            <div class="gear-item">
                <div class="gear-num">06</div>
                <div class="gear-brand">Nikon · APS-C</div>
                <div class="gear-name">Nikon D7500</div>
                <p class="gear-desc">20.9MP, 8fps burst, dual card slot. DSLR tangguh untuk berbagai kondisi shooting, ringan di genggaman.</p>
            </div>
        </div>
    </div>

    <div class="steps-wrap">
        <div class="steps">
            <div class="step">
                <div class="step-num">01</div>
                <div class="step-title">Pilih Gear</div>
                <p class="step-desc">Daftar & pilih kamera atau lensa sesuai kebutuhanmu dari koleksi kami.</p>
            </div>
            <div class="step">
                <div class="step-num">02</div>
                <div class="step-title">Tentukan Tanggal</div>
                <p class="step-desc">Pilih tanggal sewa dan cek ketersediaan secara real-time.</p>
            </div>
            <div class="step">
                <div class="step-num">03</div>
                <div class="step-title">Bayar & Konfirmasi</div>
                <p class="step-desc">Bayar via transfer, GoPay, atau OVO. Konfirmasi dikirim ke WhatsApp.</p>
            </div>
            <div class="step">
                <div class="step-num">04</div>
                <div class="step-title">Ambil & Shoot!</div>
                <p class="step-desc">Ambil di toko Bandung atau request antar. Gear siap pakai, baterai penuh.</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-logo">Lens<span>Rent</span></div>
        <div class="footer-copy">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) &mdash; © 2025 LensRent
        </div>
    </footer>

</body>
</html>