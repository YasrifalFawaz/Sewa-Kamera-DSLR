<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Sewa — LensRent</title>
    <style>
        /* Mengatur font standar PDF agar terbaca dengan baik dan ringan */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111111;
            line-height: 1.5;
            font-size: 14px;
            margin: 20px;
        }

        /* --- KOP SURAT / HEADER --- */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0a0a0a;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            color: #0a0a0a;
        }
        .header .brand {
            font-size: 14px;
            color: #c9a84c; /* Warna Emas LensRent */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        .header .doc-number {
            font-size: 11px;
            color: #777777;
            margin-top: 8px;
        }

        /* --- SUB SECTION TITLE --- */
        .section-title {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0a0a0a;
            margin-top: 25px;
            margin-bottom: 10px;
            font-weight: bold;
            border-bottom: 1px solid #2a2a2a;
            padding-bottom: 4px;
        }

        /* --- DATA TABLE STYLE --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        .data-table td.label {
            width: 30%;
            color: #555555;
            font-weight: bold;
        }
        .data-table td.colon {
            width: 3%;
            color: #555555;
        }
        .data-table td.value {
            width: 67%;
            color: #111111;
        }

        /* --- KLAUSUL PERJANJIAN --- */
        .klausul {
            font-size: 12px;
            color: #444444;
            text-align: justify;
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #c9a84c;
        }
        .klausul p {
            margin: 0 0 8px 0;
        }
        .klausul p:last-child {
            margin-bottom: 0;
        }

        /* --- TANDA TANGAN (SIGNATURES) --- */
        .signature-container {
            margin-top: 60px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 80px; /* Ruang kosong untuk tanda tangan fisik atau e-signature */
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #111111;
        }
        .signature-role {
            font-size: 12px;
            color: #777777;
            margin-top: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Kontrak Penyewaan Kamera</h1>
        <div class="brand">LensRent Studio</div>
        <div class="doc-number">Nomor Dokumen: KONTRAK/LR/{{ $kontrak->created_at->format('Ymd') }}/{{ $kontrak->id }}</div>
    </div>

    <p style="font-size: 13px; margin-bottom: 20px;">
        Perjanjian sewa-menyewa ini dibuat dan disepakati pada hari ini oleh dan di antara pihak penyedia layanan (LensRent) dengan pihak penyewa yang identitasnya tercantum di bawah ini:
    </p>

    <div class="section-title">Identitas Penyewa</div>
    <table class="data-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td class="value" style="font-weight: bold;">{{ $kontrak->nama }}</td>
        </tr>
        <tr>
            <td class="label">Nomor WhatsApp / HP</td>
            <td class="colon">:</td>
            <td class="value">{{ $kontrak->no_hp }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Lengkap</td>
            <td class="colon">:</td>
            <td class="value">{{ $kontrak->alamat }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Unit & Transaksi</div>
    <table class="data-table">
        <tr>
            <td class="label">Unit Kamera</td>
            <td class="colon">:</td>
            <td class="value" style="color: #0a0a0a; font-weight: bold;">
                {{ $kontrak->transaksi->kamera->nama_kamera ?? 'Unit tidak ditemukan' }}
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Mulai Sewa</td>
            <td class="colon">:</td>
            <td class="value">
                {{ \Carbon\Carbon::parse($kontrak->transaksi->tanggal_sewa)->format('d M Y') }}
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Pengembalian</td>
            <td class="colon">:</td>
            <td class="value">
                {{ \Carbon\Carbon::parse($kontrak->transaksi->tanggal_pengembalian)->format('d M Y') }}
            </td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td class="colon">:</td>
            <td class="value" style="text-transform: uppercase; font-weight: 500;">
                {{ $kontrak->transaksi->metode_pembayaran }}
            </td>
        </tr>
    </table>

    <div class="klausul">
        <p><strong>Syarat & Ketentuan Penyewaan:</strong></p>
        <p>1. Penyewa wajib menjaga kondisi unit kamera beserta kelengkapannya dalam kondisi prima dan bersih semenjak serah terima dilakukan.</p>
        <p>2. Keterlambatan pengembalian unit akan dikenakan denda sesuai dengan ketentuan tarif per jam yang berlaku di LensRent.</p>
        <p>3. Segala bentuk kerusakan fisik, kehilangan komponen, maupun kelalaian penggunaan sepenuhnya menjadi tanggung jawab penyewa sesuai biaya perbaikan resmi.</p>
    </div>

    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td>
                    <p>Pihak Penyedia Layanan,</p>
                    <p style="font-weight: bold; margin-top: -10px; color: #c9a84c;">LensRent Studio</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">Management LensRent</p>
                    <p class="signature-role">Sistem Terverifikasi Otomatis</p>
                </td>
                <td>
                    <p>Pihak Kedua (Penyewa),</p>
                    <p style="margin-top: -10px; color: #777777;">Tanda Tangan Fisik</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">{{ $kontrak->nama }}</p>
                    <p class="signature-role">Customer Terdaftar</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>