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

        /* Tombol Download & Kelola Kontrak */
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
        .badge-danger { background: rgba(229, 115, 115, 0.1); color: #ff8a80; border: 1px solid rgba(229, 115, 115, 0.2); }

        /* Utility Hidden Class */
        .hidden { display: none !important; }

        /* --- MODAL MANAGEMENT SYSTEM --- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(5px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-content {
            background: var(--charcoal);
            border: 1px solid var(--border);
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: modalSlideUp 0.4s ease;
        }
        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
        }
        .modal-header h3 { font-family: 'Playfair Display', serif; color: var(--white); font-size: 1.3rem; }
        .btn-close-modal { background: none; border: none; color: var(--muted); font-size: 1.8rem; cursor: pointer; line-height: 1; }
        .btn-close-modal:hover { color: var(--danger); }
        
        .form-group { margin-bottom: 1.2rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group label { display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 0.4rem; }
        .form-group input, .form-group select {
            width: 100%; background: var(--black); border: 1px solid var(--border); padding: 0.75rem 1rem; color: var(--cream); font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
        }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--gold); }
        .form-group input:disabled { opacity: 0.6; cursor: not-allowed; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.2rem; }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div>
            <a href="#" class="logo">Lens<span>Rent</span></a>
            
            <div class="nav-group">
                <span class="nav-label">Menu Admin</span>
                
                <a onclick="switchTab('kamera')" id="nav-kamera" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                    Data Kamera
                </a>

                <a onclick="switchTab('user')" id="nav-user" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Manajemen User
                </a>

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

    <main class="main-content">
        
        <header>
            <div class="page-title">
                <h1 id="dashboard-title">Pengelolaan Kamera</h1>
                <p id="dashboard-subtitle">Atur ketersediaan dan aset kamera studio Anda.</p>
            </div>
        </header>

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

        <section id="section-kamera" class="section-card">
            <div class="table-header">
                <h2 class="table-title">Daftar Inventaris Kamera</h2>
                <a href="{{ route('kamera.create') }}" class="btn btn-gold">
                    + Tambah Kamera
                </a>
            </div>

            @if(session('success') && !session('history_active'))
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
                            <th>Stok</th>
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
                                <td>{{ $kamera->stock }}</td>
                                <td>
                                    @if($kamera->stock > 0)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-warning">Habis</span>
                                    @endif
                                </td>
                                <td style="width: 150px;">
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('kamera.edit', $kamera->id) }}" class="btn btn-action btn-edit">Edit</a>
                                        <form action="{{ route('kamera.destroy', $kamera->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamera ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-delete">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;">Data kamera belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="section-user" class="section-card hidden">
            <div class="table-header">
                <h2 class="table-title">Data Pengguna Terdaftar</h2>
                <a href="{{ route('crud.create') }}" class="btn btn-gold">+ Tambah User Baru</a>
            </div>

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
                                <td style="width: 150px;">
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('crud.edit', $user->id) }}" class="btn btn-action btn-edit">Edit</a>
                                        <form action="{{ route('crud.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-delete">Hapus</button>
                                        </form>
                                    </div>
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

        <section id="section-history" class="section-card hidden">
            <div class="table-header">
                <h2 class="table-title">Riwayat Penyewaan</h2>
            </div>

            @if(session('success'))
                <div style="background: #14532d; color: #81c784; padding: 12px; margin-bottom: 20px; border: 1px solid rgba(129, 199, 132, 0.2); font-size: 0.9rem;">
                    {{ session('success') }}
                </div>
            @endif

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
                            <th>Status Sewa</th>
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
                                    @php
                                        $kontrak = \App\Models\Kontrak::where('transaksi_id', $transaksi->id)->first();
                                    @endphp

                                    {{-- Jika belum ada data kontrak --}}
                                    @if(!$kontrak)
                                        <span class="badge badge-danger">Belum Dibuat</span>

                                    {{-- Jika kontrak masih pending (Butuh Approval Admin) --}}
                                    @elseif($kontrak->status == 'pending')
                                        <button
                                            type="button"
                                            class="btn btn-action btn-gold"
                                            onclick="openKontrakModal(
                                                '{{ $transaksi->id }}',
                                                '{{ $transaksi->kamera->nama_kamera }}',
                                                '{{ $transaksi->tanggal_sewa }}',
                                                '{{ $transaksi->tanggal_pengembalian }}',
                                                '{{ $kontrak->id }}'
                                            )"
                                        >
                                            Review & Approve
                                        </button>

                                    {{-- Jika kontrak diterima --}}
                                    @elseif($kontrak->status == 'approved')
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <span class="badge badge-success">Approved</span>
                                            <a href="{{ route('kontrak.download', $kontrak->id) }}" class="btn btn-action btn-kontrak" target="_blank">
                                                🖨️ Download
                                            </a>
                                            <button type="button" style="padding: 2px 5px; font-size: 0.7rem;" class="btn btn-outline" onclick="openKontrakModal('{{ $transaksi->id }}', '{{ $transaksi->kamera->nama_kamera }}', '{{ $transaksi->tanggal_sewa }}', '{{ $transaksi->tanggal_pengembalian }}', '{{ $kontrak->id }}')">
                                                Ubah
                                            </button>
                                        </div>

                                    {{-- Jika kontrak ditolak --}}
                                    @elseif($kontrak->status == 'rejected')
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <span class="badge badge-danger">Rejected</span>
                                            <button type="button" style="padding: 2px 5px; font-size: 0.7rem;" class="btn btn-outline" onclick="openKontrakModal('{{ $transaksi->id }}', '{{ $transaksi->kamera->nama_kamera }}', '{{ $transaksi->tanggal_sewa }}', '{{ $transaksi->tanggal_pengembalian }}', '{{ $kontrak->id }}')">
                                                Ubah
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;">Belum ada riwayat penyewaan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <div id="kontrakModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Kelola Validasi Kontrak</h3>
                <button type="button" class="btn-close-modal" onclick="closeKontrakModal()">&times;</button>
            </div>
            
            <form id="formKontrakAdmin" method="POST">
                @csrf
                <input type="hidden" name="kontrak_id" id="modal_kontrak_id">
                <input type="hidden" name="transaksi_id" id="kontrak_transaksi_id">

                <div class="form-group">
                    <label>Aset Kamera</label>
                    <input type="text" id="kontrak_kamera" readonly disabled>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Mulai Sewa</label>
                        <input type="text" id="kontrak_tanggal_sewa" readonly disabled>
                    </div>
                    <div class="form-group">
                        <label>Selesai Sewa</label>
                        <input type="text" id="kontrak_tanggal_pengembalian" readonly disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label for="modal_status_select" style="color: var(--gold-lt);">Aksi Persetujuan Kontrak</label>
                    <select id="modal_status_select" name="status" required>
                        <option value="approved">Setujui & Terbitkan Kontrak (Approved)</option>
                        <option value="rejected">Tolak Berkas Kontrak (Rejected)</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeKontrakModal()">Batal</button>
                    <button type="button" class="btn btn-gold" onclick="submitStatusKontrak()">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

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

        // Fungsi Membuka Modal & Mengisi Data Awal
        function openKontrakModal(transaksiId, kamera, tanggalSewa, tanggalPengembalian, kontrakId){
            document.getElementById('kontrakModal').classList.add('open');
            document.getElementById('kontrak_transaksi_id').value = transaksiId;
            document.getElementById('kontrak_kamera').value = kamera;
            document.getElementById('kontrak_tanggal_sewa').value = tanggalSewa;
            document.getElementById('kontrak_tanggal_pengembalian').value = tanggalPengembalian;
            document.getElementById('modal_kontrak_id').value = kontrakId;
        }

        function closeKontrakModal(){
            document.getElementById('kontrakModal').classList.remove('open');
        }

        // Mengarahkan submit form ke URL / rute yang tepat secara dinamis
        function submitStatusKontrak() {
            const kontrakId = document.getElementById('modal_kontrak_id').value;
            const form = document.getElementById('formKontrakAdmin');

            if (!kontrakId) {
                alert('ID Kontrak tidak ditemukan.');
                return;
            }

            // Set action kosong agar form mengirim POST ke URL /admin/dashboard itu sendiri
            form.action = ""; 
            form.submit();
        }

        // Auto-buka tab history jika admin baru saja merubah status kontrak (opsional kenyamanan)
        @if(session('success'))
            switchTab('history');
        @endif
    </script>
</body>
</html>