<!DOCTYPE html>
<html>
<head>
    <title>Kontrak Penyewaan Kamera</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; }
        .box { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>

<div class="title">KONTRAK PENYEWAAN KAMERA</div>
<p style="text-align:center;">ID TRX: #{{ $transaksi->id }}</p>

<div class="box">
    <h4>Data Penyewa</h4>
    <table>
        <tr>
            <td>Nama</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $user->email }}</td>
        </tr>
    </table>
</div>

<div class="box">
    <h4>Detail Kamera</h4>
    <table>
        <tr>
            <td>Nama Kamera</td>
            <td>{{ $kamera->nama_kamera }}</td>
        </tr>
    </table>
</div>

<div class="box">
    <h4>Detail Transaksi</h4>
    <table>
        <tr>
            <td>Tanggal Sewa</td>
            <td>{{ $transaksi->tanggal_sewa }}</td>
        </tr>
        <tr>
            <td>Tanggal Kembali</td>
            <td>{{ $transaksi->tanggal_pengembalian }}</td>
        </tr>
    </table>
</div>

<br><br>
<p style="text-align:right;">
    Tanda Tangan Sistem<br><br><br>
    ____________
</p>

</body>
</html>