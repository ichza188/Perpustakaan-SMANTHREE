<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Bebas Perpustakaan - {{ $siswa->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 60px; line-height: 1.8; font-size: 18px; }
        .header { text-align: center; margin-bottom: 60px; }
        .header h1 { font-size: 36px; margin-bottom: 10px; }
        .content { margin: 40px 0; }
        .signature { margin-top: 100px; text-align: right; }
        .stamp { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-20deg); font-size: 80px; color: rgba(0,0,0,0.1); font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMA NEGERI 3 METRO</h1>
        <h2>SURAT BEBAS PERPUSTAKAAN</h2>
        <p>No: {{ str_pad($siswa->id, 4, '0', STR_PAD_LEFT) }}/BBS/2025</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table width="100%">
            <tr><td width="30%">Nama Siswa</td><td>: <strong>{{ strtoupper($siswa->nama) }}</strong></td></tr>
            <tr><td>NISN</td><td>: {{ $siswa->nisn }}</td></tr>
            <tr><td>Kelas</td><td>: {{ $siswa->kelas }}</td></tr>
        </table>
        <br>
        <p>Dinyatakan <strong>BEBAS PERPUSTAKAAN</strong> karena telah mengembalikan semua buku pinjaman tepat waktu.</p>
        <p>Surat ini berlaku sebagai syarat kelulusan.</p>
    </div>

    <div class="signature">
        Metro, {{ now()->format('d F Y') }}
        <br><br><br><br><br>
        <strong>(_________________________)</strong>
        <br>Kepala Perpustakaan
    </div>

    <div class="stamp">DISETUJUI</div>
</body>
</html>
