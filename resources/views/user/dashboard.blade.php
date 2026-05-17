@php
    use Illuminate\Support\Str;
@endphp

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
        .nav-group { display: flex; flex-direction: column; gap: 0.5rem; flex-grow: 1; }
        .nav-label { font-size: 0.65rem; letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted); margin-bottom: 0.5rem; padding-left: 0.5rem; }
        .nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            color: var(--cream); text-decoration: none;
            padding: 0.8rem 1rem; font-size: 0.9rem;
            border: 1px solid transparent; transition: all 0.3s;
            cursor: pointer;
        }
        .nav-item:hover, .nav-item.active { background: rgba(201,168,76,0.05); border-color: var(--border); color: var(--gold); }
        .nav-item svg { width: 18px; height: 18px; }
        .sidebar-footer { border-top: 1px solid var(--border); padding-top: 1.5rem; margin-top: auto; }
        .logout-btn {
            display: flex; align-items: center; gap: 0.75rem; color: var(--muted);
            text-decoration: none; font-size: 0.85rem; transition: color 0.3s;
            background: none; border: none; width: 100%; cursor: pointer;
        }
        .logout-btn:hover { color: var(--danger); }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 2.5rem 3rem; position: relative; z-index: 1; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; }
        .page-title h1 { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--white); }
        .page-title p { font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem; }
        .user-profile-nav { display: flex; align-items: center; gap: 0.75rem; }
        .user-avatar { width: 36px; height: 36px; background: var(--border); border: 1px solid var(--gold); display: flex; align-items: center; justify-content: center; font-weight: 500; color: var(--gold-lt); font-size: 0.85rem; }

        /* --- CAMERA GRID --- */
        .section-card { animation: fadeIn 0.5s ease both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .camera-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; }
        .camera-card { background: var(--charcoal); border: 1px solid var(--border); position: relative; transition: all 0.3s ease; display: flex; flex-direction: column; }
        .camera-card:hover { border-color: var(--gold); transform: translateY(-4px); }
        .stock-badge { position: absolute; top: 1rem; right: 1rem; z-index: 2; font-size: 0.7rem; font-weight: 500; letter-spacing: 0.05em; padding: 0.25rem 0.6rem; text-transform: uppercase; background: rgba(201, 168, 76, 0.15); color: var(--gold-lt); border: 1px solid var(--gold); }
        .stock-badge.empty { background: rgba(229, 115, 115, 0.15); color: var(--danger); border-color: var(--danger); }
        .camera-img { width: 100%; height: 180px; background: #161616; display: flex; flex-direction: column; align-items: center; justify-content: center; border-bottom: 1px solid var(--border); overflow: hidden; position: relative; }
        .camera-img img { width: 100%; height: 100%; object-fit: cover; }
        .camera-img svg { opacity: 0.25; color: var(--cream); margin-bottom: 0.5rem; }
        .camera-img span { font-size: 0.75rem; color: #444; letter-spacing: 0.05em; }
        .camera-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; gap: 0.75rem; }
        .camera-body h3 { font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--white); font-weight: 700; }
        .camera-specs-preview { font-size: 0.8rem; color: var(--muted); line-height: 1.4; }
        .camera-footer { margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(42,42,42,0.5); display: flex; justify-content: space-between; align-items: center; }
        .price-tag { font-size: 0.95rem; color: var(--gold); font-weight: 500; }
        .price-tag span { font-size: 0.75rem; color: var(--muted); font-weight: 300; }

        /* Buttons */
        .btn { font-family: 'DM Sans', sans-serif; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; padding: 0.6rem 1rem; border: 1px solid transparent; cursor: pointer; transition: all 0.3s; text-decoration: none; }
        .btn-gold { background: var(--gold); color: var(--black); }
        .btn-gold:hover { background: var(--gold-lt); transform: translateY(-1px); }
        .btn-disabled { background: #222; color: #555; cursor: not-allowed; }

        /* --- HISTORY TABLE --- */
        .table-container { background: var(--charcoal); border: 1px solid var(--border); padding: 2rem; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; }
        th { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); border-bottom: 1px solid var(--border); padding: 1rem; font-weight: 500; }
        td { padding: 1rem; border-bottom: 1px solid rgba(42,42,42,0.5); color: var(--cream); }
        .badge { display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
        .badge-success { background: rgba(129, 199, 132, 0.1); color: #81c784; border: 1px solid rgba(129, 199, 132, 0.2); }
        .badge-warning { background: rgba(244, 164, 96, 0.1); color: #f4a460; border: 1px solid rgba(244, 164, 96, 0.2); }

        .hidden { display: none !important; }

        /* ===== MODAL OVERLAY ===== */
        .modal-overlay {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 9999;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
            overflow-y: auto;
        }
        .modal-overlay.open { display: flex; }

        .modal-box {
            width: 100%;
            max-width: 560px;
            background: #111111;
            border: 1px solid #2a2a2a;
            padding: 32px;
            position: relative;
            margin: auto;
        }

        .modal-close {
            position: absolute; top: 14px; right: 16px;
            background: none; border: none; color: #888;
            font-size: 22px; cursor: pointer; line-height: 1;
            transition: color 0.2s;
        }
        .modal-close:hover { color: var(--white); }

        .modal-title {
            font-family: 'Playfair Display', serif;
            color: var(--white); font-size: 1.6rem; margin-bottom: 4px;
        }
        .modal-subtitle { color: var(--muted); font-size: 0.82rem; margin-bottom: 24px; }

        /* Form Fields */
        .field { margin-bottom: 16px; }
        .field label { display: block; margin-bottom: 7px; color: #ccc; font-size: 0.78rem; letter-spacing: 0.04em; text-transform: uppercase; }
        .field input, .field select {
            width: 100%; background: #1a1a1a; border: 1px solid #2a2a2a;
            padding: 11px 14px; color: var(--white); font-size: 0.88rem;
            font-family: 'DM Sans', sans-serif; outline: none;
            transition: border-color 0.2s;
        }
        .field input:focus, .field select:focus { border-color: var(--gold); }
        .field input[readonly] { color: #888; cursor: default; }

        /* ===== PAYMENT METHOD GRID ===== */
        .pm-section-label {
            font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase;
            color: #666; margin-bottom: 12px;
        }

        .pm-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin-bottom: 4px;
        }

        .pm-card {
            display: flex; align-items: center; gap: 12px;
            background: #1a1a1a; border: 1px solid #2a2a2a;
            padding: 12px 14px; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
            user-select: none;
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

        /* Instruksi Panel */
        .pm-instruction {
            display: none;
            background: #161616;
            border: 1px solid #2a2a2a;
            border-top: 2px solid var(--gold);
            padding: 16px 18px;
            margin-top: 10px;
            animation: slideIn 0.25s ease;
        }
        .pm-instruction.show { display: block; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }

        .pm-instruction-title {
            font-size: 0.65rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 14px; font-weight: 500;
        }

        .pm-instr-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid #1f1f1f;
            font-size: 0.82rem;
        }
        .pm-instr-row:last-child { border-bottom: none; }
        .pm-instr-row .instr-key { color: var(--muted); }
        .pm-instr-row .instr-val { color: var(--gold-lt); font-weight: 500; font-variant-numeric: tabular-nums; }

        /* Submit Button */
        .submit-btn {
            width: 100%; background: var(--gold); color: var(--black);
            border: none; padding: 14px; font-size: 0.8rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;
            margin-top: 20px; transition: background 0.2s, transform 0.1s;
            font-family: 'DM Sans', sans-serif;
        }
        .submit-btn:hover { background: var(--gold-lt); transform: translateY(-1px); }
        .pm-notice { font-size: 10px; color: #444; text-align: center; margin-top: 10px; letter-spacing: 0.03em; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <a href="#" class="logo">Lens<span>Rent</span></a>
            <div class="nav-group">
                <span class="nav-label">Menu Pelanggan</span>
                <a onclick="switchMenu('penyewaan')" id="nav-penyewaan" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    Penyewaan Kamera
                </a>
                <a onclick="switchMenu('history')" id="nav-history" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Sewa
                </a>
            </div>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
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

        <!-- SECTION 1: KATALOG -->
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
                    <div style="color: white; font-size: 18px;">Data kamera belum tersedia</div>
                @endforelse
            </div>
        </section>

        <!-- SECTION 2: HISTORY -->
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
                                <td style="color: var(--gold-lt);">#TRX-{{ $transaksi->id }}</td>
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
                                    <a href="{{ route('sewa.kontrak', $transaksi->id) }}" target="_blank" class="btn btn-primary btn-sm">
                                        Download Kontrak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align:center;">Belum ada riwayat penyewaan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- ===== MODAL SEWA ===== -->
    <div id="rentalModal" class="modal-overlay">
        <div class="modal-box">
            <button onclick="closeRentalModal()" class="modal-close">×</button>

            <h2 class="modal-title">Form Penyewaan</h2>
            <p class="modal-subtitle">Lengkapi data penyewaan kamera Anda</p>

            <form action="{{ route('sewa.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kamera_id" id="kameraId">
                <input type="hidden" name="metode_pembayaran" id="metodePembayaran">

                <!-- Nama Penyewa -->
                <div class="field">
                    <label>Nama Penyewa</label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly>
                </div>

                <!-- Kamera -->
                <div class="field">
                    <label>Kamera</label>
                    <input type="text" id="kameraNama" readonly>
                </div>

                <!-- Harga -->
                <div class="field">
                    <label>Harga Sewa</label>
                    <input type="text" id="kameraHarga" readonly>
                </div>

                <!-- Tanggal Sewa -->
                <div class="field">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_sewa" required>
                </div>

                <!-- Tanggal Kembali -->
                <div class="field">
                    <label>Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_pengembalian" required>
                </div>

                <!-- ===== METODE PEMBAYARAN ===== -->
                <div class="field" style="margin-bottom: 0;">
                    <label class="pm-section-label">Metode Pembayaran</label>

                    <div class="pm-grid">

                        <!-- GoPay -->
                        <div class="pm-card" onclick="selectPM(this,'gopay','INSTRUKSI GOPAY','0812-3456-7890','LensRent Official','2 jam setelah order')">
                            <div class="pm-icon" style="background:#00AED6;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" fill="white" fill-opacity="0.2"/><text x="12" y="16.5" text-anchor="middle" font-size="10" font-weight="900" fill="white" font-family="Arial">G</text></svg>
                            </div>
                            <div class="pm-info">
                                <div class="pm-name">GoPay</div>
                                <div class="pm-desc">E-wallet Gojek</div>
                            </div>
                        </div>

                        <!-- OVO -->
                        <div class="pm-card" onclick="selectPM(this,'ovo','INSTRUKSI OVO','0812-3456-7890','LensRent Official','2 jam setelah order')">
                            <div class="pm-icon" style="background:#4C2A86; font-size:10px;">OVO</div>
                            <div class="pm-info">
                                <div class="pm-name">OVO</div>
                                <div class="pm-desc">E-wallet OVO</div>
                            </div>
                        </div>

                        <!-- QRIS -->
                        <div class="pm-card" onclick="selectPM(this,'qris','INSTRUKSI QRIS','Scan QR di kasir','LensRent Official','2 jam setelah order')">
                            <div class="pm-icon" style="background:#E83030; font-size:10px; letter-spacing:0;">QR</div>
                            <div class="pm-info">
                                <div class="pm-name">QRIS</div>
                                <div class="pm-desc">Semua e-wallet & bank</div>
                            </div>
                        </div>

                        <!-- Debit / Kredit -->
                        <div class="pm-card" onclick="selectPM(this,'debit','INSTRUKSI DEBIT/KREDIT','Bayar saat pengambilan','LensRent Official','—')">
                            <div class="pm-icon" style="background:#2563EB;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div class="pm-info">
                                <div class="pm-name">Debit / Kredit</div>
                                <div class="pm-desc">Visa · Mastercard</div>
                            </div>
                        </div>

                        <!-- Cash (full width) -->
                        <div class="pm-card full" onclick="selectPM(this,'cash','INSTRUKSI CASH','Bayar tunai di tempat','LensRent Official','Saat pengambilan kamera')">
                            <div class="pm-icon" style="background:#1a2419; border:1px solid #3a5c30;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7bc47a" stroke-width="2"><rect x="1" y="6" width="22" height="13" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M6 9v6M18 9v6"/></svg>
                            </div>
                            <div class="pm-info">
                                <div class="pm-name">Bayar Cash di Tempat</div>
                                <div class="pm-desc">Serahkan tunai saat pengambilan kamera</div>
                            </div>
                        </div>

                    </div>

                    <!-- Instruksi Panel (muncul saat metode dipilih) -->
                    <div class="pm-instruction" id="pmInstruction">
                        <div class="pm-instruction-title" id="pmInstrTitle">INSTRUKSI GOPAY</div>
                        <div class="pm-instr-row">
                            <span class="instr-key" id="pmInstrKey1Label">Nomor / Kode</span>
                            <span class="instr-val" id="pmInstrKey1Value">—</span>
                        </div>
                        <div class="pm-instr-row">
                            <span class="instr-key">Atas Nama</span>
                            <span class="instr-val" id="pmInstrNama">—</span>
                        </div>
                        <div class="pm-instr-row">
                            <span class="instr-key">Batas Bayar</span>
                            <span class="instr-val" id="pmInstrBatas">—</span>
                        </div>
                    </div>

                </div>
                <!-- END METODE PEMBAYARAN -->

                <button type="submit" class="submit-btn" id="submitBtn">Ajukan Penyewaan</button>
                <p class="pm-notice">Transaksi aman & terenkripsi · LensRent © 2024</p>
            </form>
        </div>
    </div>

    <script>
        /* ---- NAV SWITCH ---- */
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
                title.innerText    = "Katalog Kamera";
                subtitle.innerText = "Pilih kamera terbaik untuk menangkap momen berhargamu.";
            } else {
                secHistory.classList.remove('hidden');
                secPenyewaan.classList.add('hidden');
                navHistory.classList.add('active');
                navPenyewaan.classList.remove('active');
                title.innerText    = "Riwayat Sewa Anda";
                subtitle.innerText = "Pantau status pemesanan, waktu pengembalian, dan tagihan Anda.";
            }
        }

        /* ---- MODAL OPEN / CLOSE ---- */
        function openRentalModal(id, nama, harga) {
            document.getElementById('rentalModal').classList.add('open');
            document.getElementById('kameraId').value   = id;
            document.getElementById('kameraNama').value = nama;
            document.getElementById('kameraHarga').value = 'Rp ' + harga + ' / hari';

            // Reset pilihan metode
            document.querySelectorAll('.pm-card').forEach(c => c.classList.remove('active'));
            document.getElementById('metodePembayaran').value = '';
            document.getElementById('pmInstruction').classList.remove('show');
        }

        function closeRentalModal() {
            document.getElementById('rentalModal').classList.remove('open');
        }

        // Tutup modal jika klik di luar box
        document.getElementById('rentalModal').addEventListener('click', function(e) {
            if (e.target === this) closeRentalModal();
        });

        /* ---- PILIH METODE PEMBAYARAN ---- */
        function selectPM(el, val, instrTitle, instrNomor, instrNama, instrBatas) {
            // Hilangkan active semua
            document.querySelectorAll('.pm-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');

            // Set hidden input
            document.getElementById('metodePembayaran').value = val;

            // Isi panel instruksi
            document.getElementById('pmInstrTitle').textContent    = instrTitle;
            document.getElementById('pmInstrKey1Value').textContent = instrNomor;
            document.getElementById('pmInstrNama').textContent      = instrNama;
            document.getElementById('pmInstrBatas').textContent     = instrBatas;

            // Label kolom kiri sesuai metode
            const keyLabel = document.getElementById('pmInstrKey1Label');
            if (val === 'bca' || val === 'bni') {
                keyLabel.textContent = 'Nomor VA';
            } else if (val === 'gopay' || val === 'ovo') {
                keyLabel.textContent = 'Nomor GoPay/OVO';
            } else if (val === 'qris') {
                keyLabel.textContent = 'Cara Bayar';
            } else if (val === 'debit') {
                keyLabel.textContent = 'Cara Bayar';
            } else {
                keyLabel.textContent = 'Instruksi';
            }

            // Tampilkan panel
            const panel = document.getElementById('pmInstruction');
            panel.classList.remove('show');
            void panel.offsetWidth; // reflow untuk animasi ulang
            panel.classList.add('show');
        }
    </script>
</body>
</html>