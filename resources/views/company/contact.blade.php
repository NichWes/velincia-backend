<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velincia HPL</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900">
    <header class="bg-slate-900 text-white">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between">
            <h1 class="font-bold text-xl">Velincia HPL</h1>
            <nav class="flex gap-4">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('products') }}">Produk</a>
                <a href="{{ route('about') }}">Tentang</a>
                <a href="{{ route('contact') }}">Kontak</a>
            </nav>
        </div>
    </header>

    <section class="max-w-6xl mx-auto px-6 py-20">
        <h2 class="text-4xl font-bold mb-4">Solusi Material Interior untuk Proyek Anda</h2>
        <p class="text-lg text-gray-600 mb-6">
            Velincia HPL menyediakan berbagai kebutuhan material interior seperti HPL, triplek, edging, handle, wallpanel, dan aksesoris lainnya.
        </p>
        <a href="{{ route('contact') }}" class="bg-slate-900 text-white px-6 py-3 rounded-lg">Hubungi Kami</a>
    </section>
</body>
</html>