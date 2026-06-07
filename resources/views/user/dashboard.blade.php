@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Member — LensRent</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900i&family=dm-sans:300,400,500,700&display=swap" rel="stylesheet" />

    {{-- MIDTRANS SNAP --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
        :root {
            --black:          #0a0a0a;
            --charcoal:       #111111;
            --border:         #2a2a2a;
            --gold:           #c9a84c;
            --gold-lt:        #e8c97a;
            --cream:          #f5f0e8;
            --muted:          #777777;
            --white:          #ffffff;
            --danger:         #e57373;
            --success:        #81c784;
            --sidebar-width:  260px;
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

        /* Noise texture overlay */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.5;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--charcoal);
            border-right: 1px solid var(--border);
            position: fixed; top: 0; bottom: 0; left: 0;
            z-index: 10;
            display: flex; flex-direction: column;
            padding: 2rem 1.5rem;
        }
        .sidebar::after {
            content: ''; position: absolute; top: 0; right: -1px; bottom: 0;
            width: 2px; background: linear-gradient(to bottom, transparent, var(--gold), transparent);
        }
        .sidebar .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; font-weight: 700;
            color: var(--white); text-decoration: none;
            margin-bottom: 3rem; display: block;
        }
        .sidebar .logo span { color: var(--gold); }

        .nav-group { display: flex; flex-direction: column; gap: 0.5rem; flex-grow: 1; }
        .nav-label {
            font-size: 0.65rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 0.5rem; padding-left: 0.5rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            color: var(--cream); text-decoration: none;
            padding: 0.8rem 1rem; font-size: 0.9rem;
            border: 1px solid transparent; transition: all 0.3s;
            cursor: pointer; background: none;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(201,168,76,0.05);
            border-color: var(--border); color: var(--gold);
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .sidebar-footer { border-top: 1px solid var(--border); padding-top: 1.5rem; margin-top: auto; }
        .logout-btn {
            display: flex; align-items: center; gap: 0.75rem; color: var(--muted);
            text-decoration: none; font-size: 0.85rem; transition: color 0.3s;
            background: none; border: none; width: 100%; cursor: pointer;
        }
        .logout-btn:hover { color: var(--danger); }

        /* ===================== MAIN ===================== */
        .main-content {
            margin-left: var(--sidebar-width); flex-grow: 1;
            padding: 2.5rem 3rem; position: relative; z-index: 1;
        }
        header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 2.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem;
        }
        .page-title h1 { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--white); }
        .page-title p { font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem; }

        .user-profile-nav { display: flex; align-items: center; gap: 0.75rem; }
        .user-avatar {
            width: 36px; height: 36px; background: var(--border);
            border: 1px solid var(--gold);
            display: flex; align-items: center; justify-content: center;
            font-weight: 500; color: var(--gold-lt); font-size: 0.85rem;
        }

        /* ===================== CAMERA GRID ===================== */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .section-card { animation: fadeIn 0.5s ease both; }

        .camera-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }
        .camera-card {
            background: var(--charcoal); border: 1px solid var(--border);
            position: relative; transition: all 0.3s ease;
            display: flex; flex-direction: column;
        }
        .camera-card:hover { border-color: var(--gold); transform: translateY(-4px); }

        .stock-badge {
            position: absolute; top: 1rem; right: 1rem; z-index: 2;
            font-size: 0.7rem; font-weight: 500; letter-spacing: 0.05em;
            padding: 0.25rem 0.6rem; text-transform: uppercase;
            background: rgba(201,168,76,0.15); color: var(--gold-lt); border: 1px solid var(--gold);
        }
        .stock-badge.empty {
            background: rgba(229,115,115,0.15); color: var(--danger); border-color: var(--danger);
        }

        .camera-img {
            width: 100%; height: 180px;
            background: #161616; display: flex; align-items: center; justify-content: center;
            border-bottom: 1px solid var(--border); overflow: hidden;
        }
        .camera-img img { width: 100%; height: 100%; object-fit: cover; }

        .camera-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; gap: 0.75rem; }
        .camera-body h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--white); }
        .camera-specs-preview { font-size: 0.8rem; color: var(--muted); line-height: 1.5; }
        .camera-footer {
            margin-top: auto; padding-top: 1rem;
            border-top: 1px solid rgba(42,42,42,0.5);
            display: flex; justify-content: space-between; align-items: center;
        }
        .price-tag { font-size: 0.95rem; color: var(--gold); font-weight: 500; }
        .price-tag span { font-size: 0.75rem; color: var(--muted); font-weight: 300; }

        /* ===================== BUTTONS ===================== */
        .btn {
            font-family: 'DM Sans', sans-serif; font-size: 0.75rem; font-weight: 500;
            letter-spacing: 0.08em; text-transform: uppercase;
            padding: 0.6rem 1rem; border: 1px solid transparent;
            cursor: pointer; transition: all 0.3s; text-decoration: none;
        }
        .btn-gold { background: var(--gold); color: var(--black); border-color: var(--gold); }
        .btn-gold:hover { background: var(--gold-lt); transform: translateY(-1px); }
        .btn-outline { background: transparent; color: var(--gold); border-color: var(--gold); }
        .btn-outline:hover { background: rgba(201,168,76,0.08); }
        .btn-disabled { background: #222; color: #555; cursor: not-allowed; }

        /* ===================== HISTORY TABLE ===================== */
        .table-container { background: var(--charcoal); border: 1px solid var(--border); padding: 2rem; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; }
        th {
            font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--muted); border-bottom: 1px solid var(--border);
            padding: 1rem; font-weight: 500;
        }
        td { padding: 1rem; border-bottom: 1px solid rgba(42,42,42,0.5); color: var(--cream); }
        .badge { display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
        .badge-success { background: rgba(129,199,132,0.1); color: #81c784; border: 1px solid rgba(129,199,132,0.2); }
        .badge-warning { background: rgba(244,164,96,0.1); color: #f4a460; border: 1px solid rgba(244,164,96,0.2); }

        .hidden { display: none !important; }

        /* ===================== MODAL OVERLAY ===================== */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.88); z-index: 9999;
            justify-content: center; align-items: flex-start;
            padding: 40px 20px; overflow-y: auto;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            width: 100%; max-width: 580px;
            background: #111111; border: 1px solid #2a2a2a;
            padding: 32px; position: relative; margin: auto;
        }
        .modal-close {
            position: absolute; top: 14px; right: 16px;
            background: none; border: none; color: #888;
            font-size: 22px; cursor: pointer; line-height: 1; transition: color 0.2s;
        }
        .modal-close:hover { color: var(--white); }
        .modal-title {
            font-family: 'Playfair Display', serif;
            color: var(--white); font-size: 1.6rem; margin-bottom: 4px;
        }
        .modal-subtitle { color: var(--muted); font-size: 0.82rem; margin-bottom: 24px; }

        /* ===================== FORM FIELDS ===================== */
        .field { margin-bottom: 16px; }
        .field label {
            display: block; margin-bottom: 7px; color: #ccc;
            font-size: 0.78rem; letter-spacing: 0.04em; text-transform: uppercase;
        }
        .field input, .field select {
            width: 100%; background: #1a1a1a; border: 1px solid #2a2a2a;
            padding: 11px 14px; color: var(--white); font-size: 0.88rem;
            font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.2s;
        }
        .field input:focus, .field select:focus { border-color: var(--gold); }
        .field input[readonly] { color: #888; cursor: default; }

        /* ===================== PAYMENT METHOD ===================== */
        .pm-section-label {
            font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase;
            color: #666; margin-bottom: 12px;
        }
        .pm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; margin-bottom: 4px; }
        .pm-card {
            display: flex; align-items: center; gap: 12px;
            background: #1a1a1a; border: 1px solid #2a2a2a;
            padding: 12px 14px; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            position: relative; user-select: none;
        }
        .pm-card:hover { border-color: var(--gold); background: #1e1c17; }
        .pm-card.active { border-color: var(--gold); background: rgba(201,168,76,0.06); }
        .pm-card.active::after {
            content: '✓'; position: absolute; top: 7px; right: 9px;
            font-size: 10px; color: var(--gold); font-weight: 700;
        }
        .pm-card.full { grid-column: 1 / -1; }
        .pm-icon {
            width: 40px; height: 40px; border-radius: 9px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 900; color: white; letter-spacing: -0.5px;
        }
        .pm-info .pm-name { font-size: 13px; font-weight: 500; color: #f0ebe0; line-height: 1.3; }
        .pm-info .pm-desc { font-size: 11px; color: #666; margin-top: 2px; }

        /* ===================== AI DETECTION PANEL ===================== */
        .ai-detection-panel {
            display: none;
            background: #0d0d0d;
            border: 1px solid #2a2a2a;
            border-top: 2px solid var(--gold);
            padding: 20px;
            margin-top: 10px;
            animation: fadeIn 0.3s ease;
        }
        .ai-detection-panel.show { display: block; }

        .ai-panel-header {
            display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
        }
        .ai-panel-title {
            font-size: 0.68rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--gold); font-weight: 500;
        }
        .ai-pulse {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--gold); flex-shrink: 0;
            animation: pulse 1.5s ease infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }

        #moneyVideo {
            width: 100%; border: 1px solid #2a2a2a;
            display: block; background: #000;
            max-height: 200px; object-fit: cover;
        }

        .scan-btn {
            width: 100%; background: transparent; border: 1px solid var(--gold);
            color: var(--gold); padding: 11px; font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;
            font-family: 'DM Sans', sans-serif; transition: all 0.2s; margin-bottom: 12px;
        }
        .scan-btn:hover { background: rgba(201,168,76,0.08); }
        .scan-btn:disabled { opacity: 0.4; cursor: not-allowed; }

        #moneyStatus {
            margin-top: 12px; padding: 12px 14px;
            background: #161616; border: 1px solid #1f1f1f;
            font-size: 0.83rem; color: #888; line-height: 1.5;
            min-height: 44px; transition: color 0.3s;
        }

        .scan-progress {
            height: 2px; background: #1a1a1a; margin-top: 10px; overflow: hidden;
        }
        .scan-progress-bar {
            height: 100%; width: 0%; background: var(--gold);
            transition: width 2.5s linear; display: none;
        }
        .scan-progress-bar.scanning { display: block; width: 100%; }

        /* ===================== SUBMIT BUTTON ===================== */
        .submit-btn {
            width: 100%; background: var(--gold); color: var(--black);
            border: none; padding: 14px; font-size: 0.8rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;
            margin-top: 20px; transition: background 0.2s, transform 0.1s;
            font-family: 'DM Sans', sans-serif;
        }
        .submit-btn:hover:not(:disabled) { background: var(--gold-lt); transform: translateY(-1px); }
        .submit-btn:disabled { background: #252525; color: #555; cursor: not-allowed; transform: none; }

        .pm-notice { font-size: 10px; color: #444; text-align: center; margin-top: 10px; letter-spacing: 0.03em; }

        /* Loading spinner */
        .spinner {
            display: inline-block; width: 14px; height: 14px;
            border: 2px solid rgba(0,0,0,0.2); border-top-color: var(--black);
            border-radius: 50%; animation: spin 0.6s linear infinite; vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar">
        <div>
            <a href="#" class="logo">Lens<span>Rent</span></a>
            <div class="nav-group">
                <span class="nav-label">Menu Pelanggan</span>

                <a onclick="switchMenu('penyewaan')" id="nav-penyewaan" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                    Penyewaan Kamera
                </a>

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

    <!-- ==================== MAIN ==================== -->
    <main class="main-content">
        <header>
            <div class="page-title">
                <h1 id="menu-title">Katalog Kamera</h1>
                <p id="menu-subtitle">Pilih kamera terbaik untuk menangkap momen berhargamu.</p>
            </div>
            <div class="user-profile-nav">
                <span style="font-size:0.85rem;color:var(--white)">{{ Auth::user()->name ?? 'Member LensRent' }}</span>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
            </div>
        </header>

        <!-- SECTION: KATALOG -->
        <section id="section-penyewaan" class="section-card">
            <div class="camera-grid">
                @forelse($kameras as $kamera)
                    <div class="camera-card">
                        @if($kamera->stock > 0)
                            <div class="stock-badge">Tersedia: {{ $kamera->stock }} Unit</div>
                        @else
                            <div class="stock-badge empty">Habis</div>
                        @endif

                        <div class="camera-img">
                            <img src="{{ asset('storage/' . $kamera->image) }}" alt="{{ $kamera->nama_kamera }}">
                        </div>

                        <div class="camera-body">
                            <h3>{{ $kamera->nama_kamera }}</h3>
                            <p class="camera-specs-preview">{{ Str::limit($kamera->spesifikasi, 100) }}</p>
                            <div class="camera-footer">
                                <div class="price-tag">
                                    Rp {{ number_format($kamera->harga, 0, ',', '.') }}
                                    <span>/hari</span>
                                </div>
                                @if($kamera->stock > 0)
                                    <button onclick="openRentalModal('{{ $kamera->id }}','{{ $kamera->nama_kamera }}','{{ number_format($kamera->harga, 0, ',', '.') }}')" class="btn btn-gold">
                                        Sewa Sekarang
                                    </button>
                                @else
                                    <button class="btn btn-disabled" disabled>Tidak Tersedia</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="color:var(--muted);font-size:0.9rem;padding:2rem 0;">Data kamera belum tersedia.</div>
                @endforelse
            </div>
        </section>

        <!-- SECTION: HISTORY -->
        <section id="section-history" class="section-card table-container hidden">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
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
                                <td style="color:var(--gold-lt);">#TRX-{{ $transaksi->id }}</td>
                                <td style="color:var(--white);font-weight:500;">{{ $transaksi->kamera->nama_kamera }}</td>
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
                                @if(!$transaksi->kontrak)
                                    <button
                                        onclick="openContractModal(
                                            '{{ $transaksi->id }}'
                                        )"
                                        class="btn btn-gold">
                                        Generate Kontrak
                                    </button>
                                @elseif($transaksi->kontrak->status == 'pending')
                                    <button class="btn btn-warning">
                                        Menunggu Approval
                                    </button>
                                @elseif($transaksi->kontrak->status == 'approved')
                                    <a href="{{ route(
                                        'kontrak.download',
                                        $transaksi->kontrak->id
                                    ) }}"
                                    class="btn btn-success">
                                        Download Kontrak
                                    </a>
                                @else
                                    <button class="btn btn-danger">
                                      Ditolak
                                    </button>
                                @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--muted);padding:2rem;">
                                    Belum ada riwayat penyewaan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- ==================== MODAL SEWA ==================== -->
    <div id="rentalModal" class="modal-overlay">
        <div class="modal-box">
            <button onclick="closeRentalModal()" class="modal-close">×</button>

            <h2 class="modal-title">Form Penyewaan</h2>
            <p class="modal-subtitle">Lengkapi data penyewaan kamera Anda</p>

            <form id="rentalForm">
                @csrf
                <input type="hidden" name="kamera_id"          id="kameraId">
                <input type="hidden" name="metode_pembayaran"   id="metodePembayaran">

                <div class="field">
                    <label>Nama Penyewa</label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="field">
                    <label>Kamera</label>
                    <input type="text" id="kameraNama" readonly>
                </div>

                <div class="field">
                    <label>Harga Sewa</label>
                    <input type="text" id="kameraHarga" readonly>
                </div>

                <div class="field">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_sewa" id="tanggalSewa" required>
                </div>

                <div class="field">
                    <label>Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_pengembalian" id="tanggalPengembalian" required>
                </div>

                <!-- ========== METODE PEMBAYARAN ========== -->
                <div class="field" style="margin-bottom:0;">
                    <label class="pm-section-label">Metode Pembayaran</label>

                    <div class="pm-grid">
                        <!-- Midtrans -->
                        <!-- GoPay via Midtrans -->
                        <div class="pm-card full" onclick="selectPM(this,'gopay')">
                            <div class="pm-icon" style="background:#00AED6;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="9" fill="white" fill-opacity="0.15"/>
                                    <text x="12" y="16.5" text-anchor="middle" font-size="10" font-weight="900" fill="white" font-family="Arial">M</text>
                                </svg>
                            </div>

                            <div class="pm-info">
                                <div class="pm-name">Midtrans</div>
                                <div class="pm-desc">
                                    Pembayaran online aman menggunakan Midtrans
                                </div>
                            </div>
                        </div>

                        <!-- Cash + AI Detection -->
                        <div class="pm-card full" onclick="selectPM(this,'cash')">
                            <div class="pm-icon" style="background:#1a2419;border:1px solid #3a5c30;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7bc47a" stroke-width="2">
                                    <rect x="1" y="6" width="22" height="13" rx="2"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M6 9v6M18 9v6"/>
                                </svg>
                            </div>
                            <div class="pm-info">
                                <div class="pm-name">Bayar Cash + Verifikasi Uang</div>
                                <div class="pm-desc">Scan uang asli via kamera — terdeteksi otomatis</div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== AI DETECTION PANEL ========== -->
                    <div class="ai-detection-panel" id="aiDetectionPanel">
                        <div class="ai-panel-header">
                            <div class="ai-pulse" id="aiPulse"></div>
                            <div class="ai-panel-title">Verifikasi Uang Asli</div>
                        </div>

                        <button type="button" class="scan-btn" id="startScanBtn" onclick="startMoneyDetection()">
                            📷 &nbsp; Aktifkan Kamera &amp; Scan Uang
                        </button>

                        <video id="moneyVideo" autoplay playsinline style="display:none;"></video>
                        <canvas id="moneyCanvas" style="display:none;"></canvas>

                        <div class="scan-progress">
                            <div class="scan-progress-bar" id="scanBar"></div>
                        </div>

                                                <!-- Ganti bagian dalam AI DETECTION PANEL Anda dengan struktur ini -->
                        <div id="moneyStatus">
                            Pilih metode cash lalu tekan tombol di atas untuk memindai uang Anda.
                        </div>

                        <!-- ELEMEN BARU: Untuk menampilkan visualisasi kecocokan fitur dari AI -->
                        <div id="aiVisualContainer" style="display: none; margin-top: 12px; border: 1px solid var(--gold); background: #000; padding: 5px;">
                            <p style="font-size: 0.65rem; color: var(--gold); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px; padding-left: 2px;">
                                Peta Kecocokan Fitur Unik (Kamera Kiri ↔ Template Kanan):
                            </p>
                            <img id="aiVisualImg" src="" alt="Visual Analisis AI" style="width: 100%; height: auto; display: block;">
                        </div>
                    </div>
                    <!-- END AI DETECTION PANEL -->

                </div>
                <!-- END METODE PEMBAYARAN -->

                <button type="button" class="submit-btn" id="submitBtn" disabled onclick="submitRental()">
                    Ajukan Penyewaan
                </button>
                <p class="pm-notice">Transaksi aman &amp; terenkripsi · LensRent © 2026</p>
            </form>
        </div>
    </div>
    <!-- END MODAL -->

    <div id="contractModal"
     class="modal-overlay">

        <div class="modal-box">

            <button onclick="closeContractModal()"
                    class="modal-close">

                ×

            </button>

            <h2 class="modal-title">
                Generate Kontrak
            </h2>

            <form id="contractForm">

                @csrf

                <input type="hidden"
                    id="contractTransaksiId">

                <div class="field">

                    <label>Nama</label>

                    <input type="text"
                        id="contractNama"
                        required>

                </div>

                <div class="field">

                    <label>No HP</label>

                    <input type="text"
                        id="contractNoHp"
                        required>

                </div>

                <div class="field">

                    <label>Alamat</label>

                    <textarea id="contractAlamat"
                            style="width:100%;
                                    background:#1a1a1a;
                                    border:1px solid #2a2a2a;
                                    color:white;
                                    padding:10px;"
                            required></textarea>

                </div>

                <button type="button"
                        class="submit-btn"
                        onclick="submitContract()">

                    Submit Kontrak

                </button>

            </form>

        </div>

    </div>
    <script>
        /* ==================== NAV SWITCH ==================== */
        function switchMenu(target) {
            const secPenyewaan = document.getElementById('section-penyewaan');
            const secHistory   = document.getElementById('section-history');
            const navPenyewaan = document.getElementById('nav-penyewaan');
            const navHistory   = document.getElementById('nav-history');
            const title        = document.getElementById('menu-title');
            const subtitle     = document.getElementById('menu-subtitle');

            if (target === 'penyewaan') {
                secPenyewaan.classList.remove('hidden');
                secHistory.classList.add('hidden');
                navPenyewaan.classList.add('active');
                navHistory.classList.remove('active');
                title.innerText    = 'Katalog Kamera';
                subtitle.innerText = 'Pilih kamera terbaik untuk menangkap momen berhargamu.';
            } else {
                secHistory.classList.remove('hidden');
                secPenyewaan.classList.add('hidden');
                navHistory.classList.add('active');
                navPenyewaan.classList.remove('active');
                title.innerText    = 'Riwayat Sewa Anda';
                subtitle.innerText = 'Pantau status pemesanan, waktu pengembalian, dan tagihan Anda.';
            }
        }

        /* ==================== MODAL ==================== */
        function openRentalModal(id, nama, harga) {
            document.getElementById('rentalModal').classList.add('open');
            document.getElementById('kameraId').value    = id;
            document.getElementById('kameraNama').value  = nama;
            document.getElementById('kameraHarga').value = 'Rp ' + harga + ' / hari';

            // Reset state
            document.querySelectorAll('.pm-card').forEach(c => c.classList.remove('active'));
            document.getElementById('metodePembayaran').value = '';
            document.getElementById('aiDetectionPanel').classList.remove('show');
            document.getElementById('submitBtn').disabled = true;
            stopCamera();
            resetScanUI();
        }

        function closeRentalModal() {
            document.getElementById('rentalModal').classList.remove('open');
            stopCamera();
        }

        document.getElementById('rentalModal').addEventListener('click', function(e) {
            if (e.target === this) closeRentalModal();
        });

        /* ==================== PILIH METODE PEMBAYARAN ==================== */
        function selectPM(el, val) {
            document.querySelectorAll('.pm-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('metodePembayaran').value = val;

            const aiPanel  = document.getElementById('aiDetectionPanel');
            const submitBtn = document.getElementById('submitBtn');

            if (val === 'cash') {
                aiPanel.classList.add('show');
                submitBtn.disabled = true; // tunggu verifikasi AI
                resetScanUI();
                stopCamera();
            } else {
                aiPanel.classList.remove('show');
                submitBtn.disabled = false; // midtrans langsung bisa submit
                stopCamera();
            }
        }

        /* ==================== AI MONEY DETECTION ==================== */
        let mediaStream       = null;
        let detectionInterval = null;
        let isDetecting       = false;

        function resetScanUI() {
            updateStatus('Tekan tombol di atas untuk memindai uang Anda.', '#888');
            document.getElementById('moneyVideo').style.display   = 'none';
            document.getElementById('startScanBtn').disabled      = false;
            document.getElementById('startScanBtn').innerHTML     = '📷 &nbsp; Aktifkan Kamera &amp; Scan Uang';
            document.getElementById('scanBar').classList.remove('scanning');
            
            // Reset tambahan untuk container visual
            document.getElementById('aiVisualContainer').style.display = 'none';
            document.getElementById('aiVisualImg').src = '';
            
            isDetecting = false;
        }

        function stopCamera() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(t => t.stop());
                mediaStream = null;
            }
            if (detectionInterval) {
                clearInterval(detectionInterval);
                detectionInterval = null;
            }
            isDetecting = false;
        }

        async function startMoneyDetection() {
            if (isDetecting) return;
            isDetecting = true;

            const video      = document.getElementById('moneyVideo');
            const scanBtn    = document.getElementById('startScanBtn');

            scanBtn.disabled  = true;
            scanBtn.innerHTML = '<span class="spinner"></span> &nbsp; Mengakses kamera...';
            updateStatus('⏳ Mengakses kamera perangkat Anda...', '#e8c97a');

            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = mediaStream;
                video.style.display = 'block';

                scanBtn.innerHTML = '🔍 &nbsp; Mendeteksi...';
                updateStatus('📷 Kamera aktif. Arahkan uang rupiah ke kamera, deteksi berjalan otomatis...', '#e8c97a');

                // Jalankan progress bar animasi tiap siklus
                triggerProgressBar();

                // Mulai deteksi periodik setiap 2.5 detik
                detectionInterval = setInterval(async () => {
                    await detectMoneyFrame();
                    triggerProgressBar();
                }, 2500);

            } catch (err) {
                updateStatus('❌ Kamera gagal diakses. Pastikan izin kamera sudah diberikan.', '#e57373');
                resetScanUI();
            }
        }

        function triggerProgressBar() {
            const bar = document.getElementById('scanBar');
            bar.classList.remove('scanning');
            void bar.offsetWidth; // reflow
            bar.classList.add('scanning');
        }

        async function detectMoneyFrame() {
            const video  = document.getElementById('moneyVideo');
            const canvas = document.getElementById('moneyCanvas');

            if (!video.videoWidth || !video.videoHeight) return;

            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const imageBase64 = canvas.toDataURL('image/jpeg', 0.85);

            updateStatus('🔍 Menganalisis gambar dengan AI Vision...', '#e8c97a');

            try {
                const response = await fetch("{{ route('detect.money') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ image: imageBase64 })
                });

                // Ganti logika handling fetch di dalam fungsi detectMoneyFrame() Anda dengan ini:
                // Ganti logika handling fetch di dalam fungsi detectMoneyFrame() Anda dengan blok ini:
                const data = await response.json();

                if (data.valid) {
                    const nominal = data.nominal ? ` — <b>${data.nominal}</b>` : '';
                    
                    // Kemas alasan deteksi dalam format text block info yang rapi
                    const analisaDeskripsi = data.reason ? 
                        `<div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed rgba(129,199,132,0.3); color: #ccc; font-size: 0.78rem; line-height: 1.4;">
                            ${data.reason}
                        </div>` : '';
                    
                    // Update kotak status pembayaran dengan warna sukses (hijau)
                    updateStatus('✅ ' + data.message + nominal + analisaDeskripsi, '#81c784');

                    // Aktifkan tombol Ajukan Penyewaan karena verifikasi uang fisik sukses
                    document.getElementById('submitBtn').disabled = false;

                    // Bersihkan interval kamera
                    clearInterval(detectionInterval);
                    detectionInterval = null;
                    stopCamera();

                    document.getElementById('startScanBtn').innerHTML = '✅ &nbsp; Verifikasi Berhasil';
                    document.getElementById('scanBar').classList.remove('scanning');

                } else {
                    // Jika gagal atau uang belum pas posisinya, tampilkan pesan warning merah biasa
                    updateStatus('❌ ' + data.message, '#e57373');
                    document.getElementById('submitBtn').disabled = true;
                }

            } catch (err) {
                updateStatus('⚠️ Gagal terhubung ke server AI. Mencoba ulang...', '#f4a460');
            }
        }

        function updateStatus(text, color) {
            const box = document.getElementById('moneyStatus');
            box.innerHTML   = text;
            box.style.color = color;
        }

        /* ==================== SUBMIT RENTAL ==================== */
        async function submitRental() {
            const kameraId           = document.getElementById('kameraId').value;
            const tanggalSewa        = document.getElementById('tanggalSewa').value;
            const tanggalPengembalian = document.getElementById('tanggalPengembalian').value;
            const metodePembayaran   = document.getElementById('metodePembayaran').value;

            if (!tanggalSewa || !tanggalPengembalian || !metodePembayaran) {
                alert('Harap lengkapi semua data dan pilih metode pembayaran!');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled  = true;
            submitBtn.innerHTML = '<span class="spinner"></span> &nbsp; Memproses...';

            const payload = {
                kamera_id:             kameraId,
                tanggal_sewa:          tanggalSewa,
                tanggal_pengembalian:  tanggalPengembalian,
                metode_pembayaran:     metodePembayaran
            };

            try {
                const response = await fetch("{{ route('sewa.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'Accept':        'application/json',
                        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                // ---- CASH ----
                if (metodePembayaran === 'cash') {
                    if (data.success) {
                        alert('✅ Penyewaan cash berhasil dibuat!');
                        window.location.reload();
                    } else {
                        alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
                        submitBtn.disabled  = false;
                        submitBtn.innerHTML = 'Ajukan Penyewaan';
                    }
                    return;
                }

                // ---- MIDTRANS ----
                if (data.snap_token) {
                    closeRentalModal();
                    window.snap.pay(data.snap_token, {
                        onSuccess: function() {
                            alert('✅ Pembayaran berhasil!');
                            window.location.reload();
                        },
                        onPending: function() {
                            alert('⏳ Menunggu pembayaran Anda.');
                            window.location.reload();
                        },
                        onError: function() {
                            alert('❌ Pembayaran gagal.');
                            submitBtn.disabled  = false;
                            submitBtn.innerHTML = 'Ajukan Penyewaan';
                        },
                        onClose: function() {
                            submitBtn.disabled  = false;
                            submitBtn.innerHTML = 'Ajukan Penyewaan';
                        }
                    });
                } else {
                    alert('Error: ' + (data.message || 'Gagal mendapatkan token transaksi.'));
                    submitBtn.disabled  = false;
                    submitBtn.innerHTML = 'Ajukan Penyewaan';
                }

            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau server.');
                submitBtn.disabled  = false;
                submitBtn.innerHTML = 'Ajukan Penyewaan';
            }
        }

        function openContractModal(id)
        {
            document
                .getElementById('contractModal')
                .classList.add('open');

            document
                .getElementById('contractTransaksiId')
                .value = id;
        }

        function closeContractModal()
        {
            document
                .getElementById('contractModal')
                .classList.remove('open');
        }

        async function submitContract()
        {
            const transaksiId =
                document.getElementById(
                    'contractTransaksiId'
                ).value;

            const nama =
                document.getElementById(
                    'contractNama'
                ).value;

            const noHp =
                document.getElementById(
                    'contractNoHp'
                ).value;

            const alamat =
                document.getElementById(
                    'contractAlamat'
                ).value;

            const response = await fetch("{{ route('kontrak.store') }}", {
                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body: JSON.stringify({

                        transaksi_id: transaksiId,

                        nama: nama,

                        no_hp: noHp,

                        alamat: alamat
                    })
                }
            );

            const data = await response.json();

            if(data.success)
            {
                alert(
                    'Kontrak berhasil dikirim'
                );

                location.reload();
            }
        }
    </script>

</body>
</html>