<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Bebas Perpus - VALID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-3xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-12 text-center">
            <i class="fas fa-check-circle text-10xl mb-6 animate-bounce"></i>
            <h1 class="text-6xl font-bold">VALID & BEBAS!</h1>
        </div>
        <div class="p-12 text-center space-y-6">
            <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-8 border-green-500 shadow-2xl">
                @if($siswa->foto)
                    <img src="{{ asset('storage/foto_siswa/' . $siswa->foto) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white text-6xl font-bold">
                        {{ substr($siswa->nama, 0, 2) }}
                    </div>
                @endif
            </div>
            <h2 class="text-5xl font-bold text-gray-800">{{ $siswa->nama }}</h2>
            <p class="text-3xl text-gray-600">{{ $siswa->kelas }} • {{ $siswa->nisn }}</p>
            <div class="bg-green-100 text-green-800 px-8 py-4 rounded-full text-2xl font-bold inline-block">
                BEBAS PERPUSTAKAAN
            </div>
            <p class="text-xl text-gray-600 mt-8">
                Disetujui pada {{ $siswa->tanggal_persetujuan_bebas->format('d F Y') }}
            </p>
        </div>
    </div>
</body>
</html>
