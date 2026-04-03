<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Velincia HPL' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Velincia HPL menyediakan kebutuhan material interior seperti HPL, triplek, edging, handle, wallpanel, vinyl lantai, dan aksesoris lainnya untuk kebutuhan rumah, renovasi, dan proyek contractor.' }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<body class="bg-white text-gray-900 selection:bg-slate-900 selection:text-white">
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-velincia.png') }}" alt="Logo Velincia HPL" class="w-11 h-11 object-contain rounded-xl">
                    <div>
                        <div class="font-bold text-lg leading-none">Velincia HPL</div>
                        <div class="text-xs text-gray-500">Interior Material Supplier</div>
                    </div>
                </a>

                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-slate-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Home
                    </a>
                    <a href="{{ route('products') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('products') ? 'bg-slate-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Produk
                    </a>
                    <a href="{{ route('about') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('about') ? 'bg-slate-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Tentang
                    </a>
                    <a href="{{ route('contact') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('contact') ? 'bg-slate-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Kontak
                    </a>
                </nav>

                <a href="{{ route('contact') }}"
                   class="hidden md:inline-flex bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition">
                    Hubungi Kami
                </a>
            </div>
        </div>

        <div class="md:hidden border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap gap-2">
                <a href="{{ route('home') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Home
                </a>
                <a href="{{ route('products') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('products') ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Produk
                </a>
                <a href="{{ route('about') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('about') ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Tentang
                </a>
                <a href="{{ route('contact') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('contact') ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Kontak
                </a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- Floating WhatsApp --}}
    <a href="https://wa.me/6282124636876"
       target="_blank"
       class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-3 rounded-full bg-green-600 text-white px-5 py-3 shadow-2xl hover:bg-green-700 transition">
        <span class="text-sm font-semibold">WhatsApp</span>
    </a>

    <footer class="bg-slate-950 text-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-10">
            <div class="xl:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo-velincia.png') }}" alt="Logo Velincia HPL" class="w-11 h-11 object-contain rounded-xl bg-white p-1">
                    <div>
                        <div class="font-bold text-lg">Velincia HPL</div>
                        <div class="text-sm text-slate-400">Interior Material Supplier</div>
                    </div>
                </div>

                <p class="text-sm text-slate-400 leading-7 max-w-xl">
                    Velincia HPL menyediakan berbagai kebutuhan material interior seperti HPL, triplek,
                    edging, handle, wallpanel, vinyl lantai, dan aksesoris lainnya untuk kebutuhan
                    rumah, renovasi, maupun proyek contractor.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('products') }}"
                       class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-4 py-2 text-sm text-slate-300 hover:bg-white/10">
                        Lihat Produk
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-4 py-2 text-sm text-slate-300 hover:bg-white/10">
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-4">Navigasi</h3>
                <div class="space-y-3 text-sm text-slate-400">
                    <div><a href="{{ route('home') }}" class="hover:text-white">Home</a></div>
                    <div><a href="{{ route('products') }}" class="hover:text-white">Produk</a></div>
                    <div><a href="{{ route('about') }}" class="hover:text-white">Tentang</a></div>
                    <div><a href="{{ route('contact') }}" class="hover:text-white">Kontak</a></div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-4">Informasi</h3>
                <div class="space-y-3 text-sm text-slate-400">
                    <div>
                        <div class="text-slate-500">Lokasi</div>
                        <div class="text-slate-300 mt-1">Wanasari, Cibitung, Bekasi</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Operasional</div>
                        <div class="text-slate-300 mt-1">Senin - Sabtu, 08.00 - 17.00</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Fokus</div>
                        <div class="text-slate-300 mt-1">Material Interior & Kebutuhan Proyek</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-sm text-slate-500 flex flex-col md:flex-row gap-2 md:items-center md:justify-between">
                <div>© {{ date('Y') }} Velincia HPL. All rights reserved.</div>
            </div>
        </div>
    </footer>
</body>
</html>