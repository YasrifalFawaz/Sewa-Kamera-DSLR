<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member — LensRent</title>

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
            --sidebar-width: 260px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--black);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            display: flex;
        }

        /* Efek Grain Latar Belakang */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.5;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--charcoal);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 10;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
        }

        .sidebar::before {
            content: ''; position: absolute; top: 0; right: 0; bottom: 0; width: 2px; background: var(--gold);
        }

        .sidebar .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; font-weight: 700;
            color: var(--white); text-decoration: none;
            margin-bottom: 3rem; display: block;
        }
        .sidebar .logo span { color: var(--gold); }

        .nav-group {
            display: flex; flex-direction: column; gap: 0.5rem; flex-grow: 1;
        }

        .nav-label {
            font-size: 0.65rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 0.5rem; padding-left: 0.5rem;
        }

        .nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            color: var(--cream); text-decoration: none;
            padding: 0.8rem 1rem; font-size: 0.9rem;
            border: 1px solid transparent; transition: all 0.3s;
            cursor: pointer;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(201,168,76,0.05);
            border-color: var(--border);
            color: var(--gold);
        }
        .nav-item svg { width: 18px; height: 18px; }

        .sidebar-footer {
            border-top: 1px solid var(--border); padding-top: 1.5rem; margin-top: auto;
        }

        .logout-btn {
            display: flex; align-items: center; gap: 0.75rem; color: var(--muted);
            text-decoration: none; font-size: 0.85rem; transition: color 0.3s;
            background: none; border: none; width: 100%; cursor: pointer;
        }
        .logout-btn:hover { color: var(--danger); }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1; padding: 2.5rem 3rem;
            position: relative; z-index: 1;
        }

        header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 2.5rem; border-bottom: 1px solid var(--border);
            padding-bottom: 1.5rem;
        }

        .page-title h1 {
            font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--white);
        }
        .page-title p { font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem; }

        .user-profile-nav {
            display: flex; align-items: center; gap: 0.75rem;
        }
        .user-avatar {
            width: 36px; height: 36px; background: var(--border); border: 1px solid var(--gold);
            display: flex; align-items: center; justify-content: center;
            font-weight: 500; color: var(--gold-lt); font-size: 0.85rem;
        }

        /* --- KATALOG KAMERA GRID (CARD) --- */
        .section-card {
            animation: fadeIn 0.5s ease both;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .camera-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .camera-card {
            background: var(--charcoal);
            border: 1px solid var(--border);
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .camera-card:hover {
            border-color: var(--gold);
            transform: translateY(-4px);
        }

        .stock-badge {
            position: absolute; top: 1rem; right: 1rem; z-index: 2;
            font-size: 0.7rem; font-weight: 500; letter-spacing: 0.05em;
            padding: 0.25rem 0.6rem; text-transform: uppercase;
            background: rgba(201, 168, 76, 0.15); color: var(--gold-lt); border: 1px solid var(--gold);
        }
        .stock-badge.empty {
            background: rgba(229, 115, 115, 0.15); color: var(--danger); border-color: var(--danger);
        }

        .camera-img {
            width: 100%; height: 180px; background: #161616;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            border-bottom: 1px solid var(--border); overflow: hidden; position: relative;
        }
        /* Tambahan jika menggunakan tag <img> asli nanti */
        .camera-img img { width: 100%; height: 100%; object-fit: cover; }

        .camera-img svg { opacity: 0.25; color: var(--cream); margin-bottom: 0.5rem; }
        .camera-img span { font-size: 0.75rem; color: #444; letter-spacing: 0.05em; }

        .camera-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; gap: 0.75rem; }
        .camera-body h3 { font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--white); font-weight: 700; }
        
        .camera-specs-preview { font-size: 0.8rem; color: var(--muted); line-height: 1.4; }

        .camera-footer {
            margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(42,42,42,0.5);
            display: flex; justify-content: space-between; align-items: center;
        }
        .price-tag { font-size: 0.95rem; color: var(--gold); font-weight: 500; }
        .price-tag span { font-size: 0.75rem; color: var(--muted); font-weight: 300; }

        /* Buttons */
        .btn {
            font-family: 'DM Sans', sans-serif; font-size: 0.75rem; font-weight: 500;
            letter-spacing: 0.08em; text-transform: uppercase; padding: 0.6rem 1rem;
            border: 1px solid transparent; cursor: pointer; transition: all 0.3s; text-decoration: none;
        }
        .btn-gold { background: var(--gold); color: var(--black); }
        .btn-gold:hover { background: var(--gold-lt); transform: translateY(-1px); }
        .btn-disabled { background: #222; color: #555; cursor: not-allowed; }

        /* --- RIWAYAT SEWA (TABLE) --- */
        .table-container {
            background: var(--charcoal); border: 1px solid var(--border); padding: 2rem;
        }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; }
        th {
            font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--muted); border-bottom: 1px solid var(--border); padding: 1rem; font-weight: 500;
        }
        td { padding: 1rem; border-bottom: 1px solid rgba(42,42,42,0.5); color: var(--cream); }
        
        /* Status Badges */
        .badge {
            display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 500;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .badge-success { background: rgba(129, 199, 132, 0.1); color: #81c784; border: 1px solid rgba(129, 199, 132, 0.2); }
        .badge-warning { background: rgba(244, 164, 96, 0.1); color: #f4a460; border: 1px solid rgba(244, 164, 96, 0.2); }

        .hidden { display: none !important; }
    </style>
</head>
<body>

    <!-- SIDEBAR NAVIGATION USER -->
    <aside class="sidebar">
        <div>
            <a href="#" class="logo">Lens<span>Rent</span></a>
            
            <div class="nav-group">
                <span class="nav-label">Menu Pelanggan</span>
                
                <!-- Nav 1: Penyewaan -->
                <a onclick="switchMenu('penyewaan')" id="nav-penyewaan" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                    Penyewaan Kamera
                </a>

                <!-- Nav 2: History -->
                <a onclick="switchMenu('history')" id="nav-history" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Sewa
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="main-content">
        
        <header>
            <div class="page-title">
                <h1 id="menu-title">Katalog Kamera</h1>
                <p id="menu-subtitle">Pilih kamera terbaik untuk menangkap momen berhargamu.</p>
            </div>
            <div class="user-profile-nav">
                <span style="font-size: 0.85rem; color: var(--white)">{{ Auth::user()->name ?? 'Member LensRent' }}</span>
                <div class="user-avatar">U</div>
            </div>
        </header>

        <!-- SECTION 1: KATALOG PENYEWAN (GRID CARDS) -->
        <section id="section-penyewaan" class="section-card">
            <div class="camera-grid">
                
                <!-- Card Kamera 1 -->
                <div class="camera-card">
                    <div class="stock-badge">Tersedia: 3 Unit</div>
                    <div class="camera-img">
                        <!-- Jika ada aset foto masukkan disini: <img src="url_foto_kamera" alt="Sony a7IV"> -->
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        <span>Sony Alpha a7 IV</span>
                    </div>
                    <div class="camera-body">
                        <h3>Sony Alpha a7 IV</h3>
                        <p class="camera-specs-preview">Sensor Full-Frame 33MP, Perekaman Video 4K 60p, Stabilisasi Gambar 5-Axis In-Body.</p>
                        <div class="camera-footer">
                            <div class="price-tag">Rp 350k <span>/hari</span></div>
                            <a href="#" class="btn btn-gold">Sewa Sekarang</a>
                        </div>
                    </div>
                </div>

                <!-- Card Kamera 2 -->
                <div class="camera-card">
                    <div class="stock-badge">Tersedia: 1 Unit</div>
                    <div class="camera-img">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        <span>Canon EOS R5</span>
                    </div>
                    <div class="camera-body">
                        <h3>Canon EOS R5</h3>
                        <p class="camera-specs-preview">Sensor 45MP, Video 8K RAW internal, Dual Pixel CMOS AF II super cepat.</p>
                        <div class="camera-footer">
                            <div class="price-tag">Rp 500k <span>/hari</span></div>
                            <a href="#" class="btn btn-gold">Sewa Sekarang</a>
                        </div>
                    </div>
                </div>

                <!-- Card Kamera 3 (Stok Habis) -->
                <div class="camera-card">
                    <div class="stock-badge empty">Habis</div>
                    <div class="camera-img">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        <span>Fujifilm X-T4</span>
                    </div>
                    <div class="camera-body">
                        <h3>Fujifilm X-T4</h3>
                        <p class="camera-specs-preview">Sensor APS-C 26.1MP, X-Processor 4, Simulasi Film Legendaris Fujifilm.</p>
                        <div class="camera-footer">
                            <div class="price-tag">Rp 250k <span>/hari</span></div>
                            <button class="btn btn-disabled" disabled>Tidak Tersedia</button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- SECTION 2: HISTORY PENYEWAAN (TABLE VIEW) -->
        <section id="section-history" class="section-card table-container hidden">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Kamera</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Biaya</th>
                            <th>Status Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="color: var(--gold-lt);">#LR-98241</td>
                            <td style="color: var(--white); font-weight: 500;">Sony Alpha a7 IV</td>
                            <td>12 Mei 2026</td>
                            <td>14 Mei 2026</td>
                            <td>Rp 700.000</td>
                            <td><span class="badge badge-success">Selesai / Kembali</span></td>
                        </tr>
                        <tr>
                            <td style="color: var(--gold-lt);">#LR-99102</td>
                            <td style="color: var(--white); font-weight: 500;">Canon EOS R5</td>
                            <td>16 Mei 2026</td>
                            <td>18 Mei 2026</td>
                            <td>Rp 1.000.000</td>
                            <td><span class="badge badge-warning">Sedang Digunakan</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- JAVASCRIPT LOGIC NAV SWITCH -->
    <script>
        function switchMenu(target) {
            const secPenyewaan = document.getElementById('section-penyewaan');
            const secHistory = document.getElementById('section-history');
            
            const navPenyewaan = document.getElementById('nav-penyewaan');
            const navHistory = document.getElementById('nav-history');

            const title = document.getElementById('menu-title');
            const subtitle = document.getElementById('menu-subtitle');

            if (target === 'penyewaan') {
                secPenyewaan.classList.remove('hidden');
                secHistory.classList.add('hidden');
                
                navPenyewaan.classList.add('active');
                navHistory.classList.remove('active');

                title.innerText = "Katalog Kamera";
                subtitle.innerText = "Pilih kamera terbaik untuk menangkap momen berhargamu.";
            } else if (target === 'history') {
                secHistory.classList.remove('hidden');
                secPenyewaan.classList.add('hidden');
                
                navHistory.classList.add('active');
                navPenyewaan.classList.remove('active');

                title.innerText = "Riwayat Sewa Anda";
                subtitle.innerText = "Pantau status pemesanan, waktu pengembalian, dan tagihan Anda.";
            }
        }
    </script>
</body>
</html>