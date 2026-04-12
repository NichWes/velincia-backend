@extends('layouts.company')

@php
    $title = 'Velincia HPL | Home';
@endphp

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.10),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.07),transparent_28%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:60px_60px] opacity-20"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-28">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-sm text-slate-200 mb-6">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Velincia HPL • Interior Material Supplier
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight max-w-4xl">
                        Solusi Material Interior
                        <span class="text-slate-300 block mt-1">untuk proyek anda</span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-300 leading-8 max-w-2xl">
                        Velincia HPL menyediakan berbagai kebutuhan material interior seperti HPL, triplek,
                        edging, handle, wallpanel, vinyl lantai, dan aksesoris lainnya.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white text-slate-900 px-6 py-3.5 font-semibold hover:bg-slate-100 transition">
                            Lihat Produk
                        </a>

                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/15 text-white px-6 py-3.5 font-semibold hover:bg-white/10 transition">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- INTRO STATS --}}
    <section class="bg-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-[28px] border border-gray-200 p-8 bg-slate-50">
                    <div class="text-sm text-gray-500">Fokus Utama</div>
                    <div class="text-2xl font-bold mt-2">Material Interior</div>
                    <p class="mt-3 text-gray-600 leading-7">
                        Menyediakan kebutuhan bahan untuk interior rumah, renovasi, dan proyek.
                    </p>
                </div>

                <div class="rounded-[28px] border border-gray-200 p-8 bg-slate-900 text-white">
                    <div class="text-sm text-slate-300">Target Pengguna</div>
                    <div class="text-2xl font-bold mt-2">Pelanggan & Contractor</div>
                    <p class="mt-3 text-slate-300 leading-7">
                        Cocok untuk kebutuhan individu maupun contractor.
                    </p>
                </div>

                <div class="rounded-[28px] border border-gray-200 p-8 bg-white">
                    <div class="text-sm text-gray-500">Arah Pengembangan</div>
                    <div class="text-2xl font-bold mt-2">Digital & Modern</div>
                    <p class="mt-3 text-gray-600 leading-7">
                        Velincia HPL sedang bertumbuh menuju sistem digital yang lebih efisien dan terintegrasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CATEGORY SECTION --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
                <div class="max-w-3xl">
                    <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Kategori Utama</div>
                    <h2 class="text-3xl md:text-4xl font-bold mt-3">Kategori material yang paling relevan untuk kebutuhan interior</h2>
                    <p class="text-gray-600 mt-4 leading-7">
                        Kami menampilkan kategori yang umum dibutuhkan untuk proyek interior agar pelanggan
                        lebih mudah memahami pilihan material yang tersedia.
                    </p>
                </div>

                <a href="{{ route('products') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-slate-900 text-white px-5 py-3 font-medium hover:bg-slate-800 transition">
                    Jelajahi Katalog
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                @forelse($topCategories as $category)
                    <div class="group rounded-[30px] bg-white border border-gray-200 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-lg font-bold">
                            {{ strtoupper(substr($category, 0, 1)) }}
                        </div>

                        <h3 class="text-2xl font-bold mt-6">{{ $category }}</h3>
                        <p class="text-gray-600 mt-3 leading-7">
                            Material kategori {{ strtolower($category) }} yang dapat menunjang kebutuhan proyek interior secara lebih rapi.
                        </p>

                        <div class="mt-6">
                            <a href="{{ route('products') }}"
                               class="inline-flex items-center text-sm font-semibold text-slate-900 group-hover:translate-x-1 transition">
                                Lihat kategori →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-[30px] bg-white border border-gray-200 p-10 text-center text-gray-500">
                        Belum ada kategori aktif untuk ditampilkan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- WHY SECTION --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-5">
                    <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Kenapa Velincia HPL</div>
                    <h2 class="text-3xl md:text-4xl font-bold mt-3 leading-tight">
                        Dibangun untuk kebutuhan nyata proyek anda
                    </h2>
                    <p class="text-gray-600 mt-5 leading-8">
                        Velincia HPL berfokus pada material yang relevan untuk pekerjaan interior,
                        dengan pendekatan yang lebih praktis untuk pelanggan maupun contractor.
                    </p>
                </div>

                <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-[28px] bg-slate-900 text-white p-7">
                        <div class="text-lg font-bold">Pilihan material lebih relevan</div>
                        <p class="text-slate-300 mt-3 leading-7">
                            Fokus pada kategori yang memang dibutuhkan untuk pekerjaan interior dan finishing.
                        </p>
                    </div>

                    <div class="rounded-[28px] bg-white border border-gray-200 p-7">
                        <div class="text-lg font-bold">Cocok untuk pembelian proyek</div>
                        <p class="text-gray-600 mt-3 leading-7">
                            Mendukung pola pembelian yang bertahap dan lebih dekat dengan kebutuhan lapangan.
                        </p>
                    </div>

                    <div class="rounded-[28px] bg-white border border-gray-200 p-7">
                        <div class="text-lg font-bold">Estimasi harga lebih jelas</div>
                        <p class="text-gray-600 mt-3 leading-7">
                            Katalog membantu memberi gambaran awal biaya material yang diperlukan.
                        </p>
                    </div>

                    <div class="rounded-[28px] bg-white border border-gray-200 p-7">
                        <div class="text-lg font-bold">Siap bertumbuh digital</div>
                        <p class="text-gray-600 mt-3 leading-7">
                            Website dan sistem digital menjadi langkah awal untuk pelayanan yang lebih modern.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[36px] overflow-hidden bg-slate-950 text-white relative">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.12),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.08),transparent_28%)]"></div>

                <div class="relative px-8 py-10 md:px-12 md:py-14 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="max-w-2xl">
                        <div class="text-sm uppercase tracking-[0.2em] text-slate-400 font-semibold">Siap konsultasi</div>
                        <h2 class="text-3xl md:text-4xl font-bold mt-3 leading-tight">
                            Butuh material untuk proyek interior Anda?
                        </h2>
                        <p class="text-slate-300 mt-4 leading-7">
                            Hubungi Velincia HPL untuk mendapatkan informasi produk, estimasi awal,
                            dan kebutuhan material yang sesuai dengan proyek Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white text-slate-900 px-6 py-3.5 font-semibold hover:bg-slate-100 transition">
                            Jelajahi Produk
                        </a>

                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/15 text-white px-6 py-3.5 font-semibold hover:bg-white/10 transition">
                            Hubungi Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection