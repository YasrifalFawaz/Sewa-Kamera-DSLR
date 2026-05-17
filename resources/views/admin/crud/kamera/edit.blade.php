<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kamera — LensRent</title>

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
            padding: 3rem 1rem;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.5;
        }

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
        .back-btn:hover { color: var(--gold); border-color: var(--gold); }

        .card {
            position: relative; z-index: 1;
            background: var(--charcoal);
            border: 1px solid var(--border);
            width: 100%; max-width: 600px;
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

        .form-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;
        }
        .full-width { grid-column: span 2; }

        .field { margin-bottom: 0.25rem; }

        label {
            display: block;
            font-size: 0.72rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 0.6rem;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
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

        textarea { resize: vertical; min-height: 90px; }

        input[type="file"] {
            width: 100%; background: var(--black);
            color: var(--muted); font-size: 0.85rem;
            border: 1px dashed var(--border); padding: 0.85rem; cursor: pointer;
        }

        .input-hint {
            font-size: 0.75rem; color: var(--muted); margin-top: 0.4rem; display: block;
        }

        input:focus, textarea:focus, select:focus, input[type="file"]:hover { border-color: var(--gold); }

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
            margin-top: 1.5rem;
        }
        .btn-submit:hover { background: var(--gold-lt); transform: translateY(-1px); }
    </style>
</head>
<body>

    <!-- Tombol Kembali ke Halaman Utama Admin -->
    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline; margin-right:4px; vertical-align:middle;">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="card">
        <h1 class="card-title">Edit Kamera</h1>
        <p class="card-sub">Perbarui informasi spesifikasi atau ketersediaan unit armada kamera.</p>

        <!-- PENTING: Menggunakan method PUT untuk proses update data -->
        <form method="POST" action="{{ route('kamera.update', $kamera->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                
                <!-- 1. Nama Kamera -->
                <div class="field full-width">
                    <label for="nama_kamera">Nama / Seri Kamera</label>
                    <input 
                        type="text" 
                        id="nama_kamera" 
                        name="nama_kamera" 
                        value="{{ old('nama_kamera', $kamera->nama_kamera) }}" 
                        required 
                        autofocus
                    >
                    @error('nama_kamera') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 2. Brand Kamera -->
                <div class="field">
                    <label for="brand">Brand / Merk</label>
                    <select id="brand" name="brand" required>
                        <option value="Sony" {{ old('brand', $kamera->brand) == 'Sony' ? 'selected' : '' }}>Sony</option>
                        <option value="Canon" {{ old('brand', $kamera->brand) == 'Canon' ? 'selected' : '' }}>Canon</option>
                        <option value="Fujifilm" {{ old('brand', $kamera->brand) == 'Fujifilm' ? 'selected' : '' }}>Fujifilm</option>
                        <option value="Nikon" {{ old('brand', $kamera->brand) == 'Nikon' ? 'selected' : '' }}>Nikon</option>
                        <option value="Panasonic" {{ old('brand', $kamera->brand) == 'Panasonic' ? 'selected' : '' }}>Panasonic</option>
                    </select>
                    @error('brand') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 3. Gambar Kamera (Opsional saat Edit) -->
                <div class="field">
                    <label for="image">Ganti Foto Kamera</label>
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                    >
                    <span class="input-hint">File saat ini: <strong style="color: var(--gold-lt);">{{ $kamera->image }}</strong></span>
                    <span class="input-hint" style="font-size:0.7rem; color: var(--muted);">(Kosongkan jika tidak ingin mengubah foto)</span>
                    @error('image') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 4. Harga Sewa -->
                <div class="field">
                    <label for="harga">Harga Sewa / Hari (Rp)</label>
                    <input 
                        type="number" 
                        id="harga" 
                        name="harga" 
                        value="{{ old('harga', $kamera->harga) }}" 
                        required
                    >
                    @error('harga') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 5. Stok Unit -->
                <div class="field">
                    <label for="stock">Jumlah Stok (Unit)</label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        value="{{ old('stock', $kamera->stock) }}" 
                        required
                    >
                    @error('stock') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 6. Deskripsi -->
                <div class="field full-width">
                    <label for="deskripsi">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $kamera->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 7. Spesifikasi -->
                <div class="field full-width">
                    <label for="spesifikasi">Spesifikasi Teknis</label>
                    <textarea id="spesifikasi" name="spesifikasi" required>{{ old('spesifikasi', $kamera->spesifikasi) }}</textarea>
                    @error('spesifikasi') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- 8. Kelengkapan -->
                <div class="field full-width">
                    <label for="kelengkapan">Kelengkapan Paket Sewa</label>
                    <textarea id="kelengkapan" name="kelengkapan" required>{{ old('kelengkapan', $kamera->kelengkapan) }}</textarea>
                    @error('kelengkapan') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

            </div>

            <button type="submit" class="btn-submit">
                Perbarui Data Kamera
            </button>
        </form>
    </div>

</body>
</html>