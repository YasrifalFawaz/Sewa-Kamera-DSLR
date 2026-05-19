<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — LensRent</title>

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
            flex-content: space-between;
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

        /* Stats Cards Widgets */
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem; margin-bottom: 2.5rem;
        }
        .stat-box {
            background: var(--charcoal); border: 1px solid var(--border);
            padding: 1.5rem; position: relative;
        }
        .stat-box::before {
            content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: var(--border);
        }
        .stat-box:hover::before { background: var(--gold); }
        .stat-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 1.75rem; color: var(--white); margin-top: 0.5rem; }

        /* --- DATA TABLES & CRUD COMPONENTS --- */
        .section-card {
            background: var(--charcoal); border: 1px solid var(--border); padding: 2rem;
            animation: fadeIn 0.5s ease both;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .table-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;
        }
        .table-title { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--white); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-family: 'DM Sans', sans-serif; font-size: 0.8rem; font-weight: 500;
            letter-spacing: 0.05em; text-transform: uppercase; padding: 0.65rem 1.2rem;
            border: 1px solid transparent; cursor: pointer; transition: all 0.3s; text-decoration: none;
        }
        .btn-gold { background: var(--gold); color: var(--black); }
        .btn-gold:hover { background: var(--gold-lt); }
        
        .btn-outline { border-color: var(--border); color: var(--cream); background: transparent; }
        .btn-outline:hover { border-color: var(--gold); color: var(--gold); }

        .btn-action { padding: 0.4rem 0.6rem; font-size: 0.75rem; letter-spacing: 0; text-transform: none; }
        .btn-edit { border-color: var(--border); color: var(--gold-lt); background: transparent; }
        .btn-edit:hover { border-color: var(--gold); background: rgba(201,168,76,0.05); }
        .btn-delete { border-color: var(--border); color: var(--danger); background: transparent; }
        .btn-delete:hover { border-color: var(--danger); background: rgba(229,115,115,0.05); }

        /* Tombol Download Kontrak */
        .btn-kontrak {
            border-color: var(--border);
            color: var(--cream);
            background: transparent;
        }
        .btn-kontrak:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: rgba(201,168,76,0.05);
        }

        /* Table Style */
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; }
        th {
            font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--muted); border-bottom: 1px solid var(--border); padding: 1rem; font-weight: 500;
        }
        td { padding: 1rem; border-bottom: 1px solid rgba(42,42,42,0.5); color: var(--cream); vertical-align: middle; }
        tr:hover td { background: rgba(255,255,255,0.01); }

        /* Badge Status */
        .badge {
            display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 500;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .badge-success { background: rgba(129, 199, 132, 0.1); color: #81c784; border: 1px solid rgba(129, 199, 132, 0.2); }
        .badge-warning { background: rgba(244, 164, 96, 0.1); color: #f4a460; border: 1px solid rgba(244, 164, 96, 0.2); }

        /* Utility Hidden Class */
        .hidden { display: none !important; }
    </style>
</head>
<body>

    <!-- SIDEBAR NAVIGATION -->
    <aside class="sidebar">
        <div>
            <a href="#" class="logo">Lens<span>Rent</span></a>
            
            <div class="nav-group">
                <span class="nav-label">Menu Admin</span>
                
                <!-- Nav Tab 1: Kamera -->
                <a onclick="switchTab('kamera')" id="nav-kamera" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                    Data Kamera
                </a>

                <!-- Nav Tab 2: User -->
                <a onclick="switchTab('user')" id="nav-user" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Manajemen User
                </a>

                <!-- Nav Tab 3: History Penyewa -->
                <a onclick="switchTab('history')" id="nav-history" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    History Penyewa
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
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="main-content">
        
        <header>
            <div class="page-title">
                <h1 id="dashboard-title">Pengelolaan Kamera</h1>
                <p id="dashboard-subtitle">Atur ketersediaan dan aset kamera studio Anda.</p>
            </div>
        </header>

        <!-- STATS WIDGETS BAR -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Kamera</div>
                <div class="stat-value">{{ $totalKamera }} Unit</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Sedang Disewa</div>
                <div class="stat-value">{{ $sedangDisewa }} Kamera</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-value">{{ $totalUser }} Terdaftar</div>
            </div>
        </div>
        <!-- SECTION: CRUD KAMERA -->
        <section id="section-kamera" class="section-card">
            <div class="table-header">
                <h2 class="table-title">Daftar Inventaris Kamera</h2>
                <a href="{{ route('kamera.create') }}" class="btn btn-gold">
                    + Tambah Kamera
                </a>
            </div>

            @if(session('success'))
                <div style="background: #14532d; color: white; padding: 10px; margin-bottom: 15px; border-radius: 8px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kamera</th>
                            <th>Brand</th>
                            <th>Harga Sewa /Hari</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kameras as $kamera)
                            <tr>
                                <td>#CAM-{{ $kamera->id }}</td>
                                <td style="color: var(--white); font-weight: 500;">{{ $kamera->nama_kamera }}</td>
                                <td>{{ $kamera->brand }}</td>
                                <td>Rp {{ number_format($kamera->harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($kamera->stock > 0)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-warning">Habis</span>
                                    @endif
                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <a href="{{ route('kamera.edit', $kamera->id) }}" class="btn btn-action btn-edit">Edit</a>
                                    <form
                                        action="{{ route('kamera.destroy', $kamera->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kamera ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;">Data kamera belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SECTION: CRUD USER -->
        <section id="section-user" class="section-card hidden">
            <div class="table-header">
                <h2 class="table-title">Data Pengguna Terdaftar</h2>
                <a href="{{ route('crud.create') }}" class="btn btn-gold">+ Tambah User Baru</a>
            </div>

            @if(session('success'))
                <div style="background: #14532d; color: white; padding: 10px; margin-bottom: 15px; border-radius: 8px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID User</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role Akun</th>
                            <th>Tanggal Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>#USR-{{ $user->id }}</td>
                                <td style="color: var(--white); font-weight: 500;">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge badge-warning">Administrator</span>
                                    @else
                                        <span class="badge badge-success">Customer</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td style="display: flex; gap: 5px;">
                                    <a href="{{ route('crud.edit', $user->id) }}" class="btn btn-action btn-edit">Edit</a>
                                    <form
                                        action="{{ route('crud.destroy', $user->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;">Data user belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SECTION: HISTORY PENYEWA -->
        <section id="section-history" class="section-card hidden">
            <div class="table-header">
                <h2 class="table-title">Riwayat Penyewaan</h2>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Penyewa</th>
                            <th>Kamera</th>
                            <th>Tanggal Sewa</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Kontrak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                            <tr>
                                <td style="color: var(--gold-lt);">#TRX-{{ $transaksi->id }}</td>
                                <td style="color: var(--white); font-weight: 500;">{{ $transaksi->user->name ?? '-' }}</td>
                                <td style="color: var(--white); font-weight: 500;">{{ $transaksi->kamera->nama_kamera }}</td>
                                <td>{{ $transaksi->tanggal_sewa }}</td>
                                <td>{{ $transaksi->tanggal_pengembalian }}</td>
                                <td>{{ strtoupper($transaksi->metode_pembayaran) }}</td>
                                <td>
                                    @if(now() > $transaksi->tanggal_pengembalian)
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Disewa</span>
                                    @endif
                                </td>
                                <td>
                                    <a
                                        href="{{ route('sewa.kontrak', $transaksi->id) }}"
                                        target="_blank"
                                        class="btn btn-action btn-kontrak"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Download Kontrak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;">Belum ada riwayat penyewaan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- JAVASCRIPT UNTUK SIMULASI PERPINDAHAN NAVBAR CRUD -->
    <script>
        function switchTab(target) {
            const secKamera  = document.getElementById('section-kamera');
            const secUser    = document.getElementById('section-user');
            const secHistory = document.getElementById('section-history');

            const navKamera  = document.getElementById('nav-kamera');
            const navUser    = document.getElementById('nav-user');
            const navHistory = document.getElementById('nav-history');

            const title    = document.getElementById('dashboard-title');
            const subtitle = document.getElementById('dashboard-subtitle');

            // Sembunyikan semua section & nonaktifkan semua nav
            [secKamera, secUser, secHistory].forEach(s => s.classList.add('hidden'));
            [navKamera, navUser, navHistory].forEach(n => n.classList.remove('active'));

            if (target === 'kamera') {
                secKamera.classList.remove('hidden');
                navKamera.classList.add('active');
                title.innerText    = "Pengelolaan Kamera";
                subtitle.innerText = "Atur ketersediaan dan aset kamera studio Anda.";

            } else if (target === 'user') {
                secUser.classList.remove('hidden');
                navUser.classList.add('active');
                title.innerText    = "Manajemen Pengguna";
                subtitle.innerText = "Kelola data akun, verifikasi customer, dan hak akses admin.";

            } else if (target === 'history') {
                secHistory.classList.remove('hidden');
                navHistory.classList.add('active');
                title.innerText    = "History Penyewa";
                subtitle.innerText = "Pantau seluruh riwayat transaksi dan status penyewaan kamera.";
            }
        }
    </script>
</body>
</html>