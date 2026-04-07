@extends('layouts.company')

@php
    $title = 'Velincia HPL | Produk';
@endphp

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.10),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.06),transparent_28%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:60px_60px] opacity-20"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-sm text-slate-200 mb-6">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Katalog Produk Velincia HPL
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight max-w-4xl">
                        Material interior 
                        <span class="text-slate-300 block mt-1">untuk kebutuhan rumah, renovasi, dan proyek</span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-300 leading-8 max-w-2xl">
                        Katalog ini menampilkan material tersedia pada Velincia HPL.
                        Cocok untuk kebutuhan pelanggan individu maupun contractor yang membutuhkan
                        pilihan bahan yang lebih terstruktur.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white text-slate-900 px-6 py-3.5 font-semibold hover:bg-slate-100 transition">
                            Tanya Ketersediaan
                        </a>

                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/15 text-white px-6 py-3.5 font-semibold hover:bg-white/10 transition">
                            Kembali ke Home
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-[32px] border border-white/10 bg-white/5 backdrop-blur p-4">
                        <div class="rounded-[28px] bg-white text-slate-900 p-6 shadow-2xl">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="rounded-3xl bg-slate-100 p-5">
                                    <div class="text-sm text-gray-500">Produk</div>
                                    <div class="text-4xl font-bold mt-2">{{ $totalProducts }}</div>
                                    <div class="text-sm text-gray-600 mt-3 leading-6">
                                        Material yang ada pada katalog.
                                    </div>
                                </div>

                                <div class="rounded-3xl bg-slate-900 text-white p-5">
                                    <div class="text-sm text-slate-300">Kategori</div>
                                    <div class="text-4xl font-bold mt-2">{{ $categories->count() }}</div>
                                    <div class="text-sm text-slate-300 mt-3 leading-6">
                                        Jenis kategori material yang tersedia.
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-gray-200 p-5 col-span-2">
                                    <div class="text-sm text-gray-500">Kategori Populer</div>

                                    <div class="mt-4 flex flex-wrap gap-3">
                                        @forelse($categories->take(6) as $category)
                                            <span class="inline-flex px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-medium">
                                                {{ $category }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500">Belum ada kategori aktif.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- QUICK VALUE --}}
    <section class="bg-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-[28px] border border-gray-200 p-8 bg-slate-50">
                    <div class="text-sm text-gray-500">Pilihan Material</div>
                    <div class="text-2xl font-bold mt-2">Lebih Terstruktur</div>
                    <p class="mt-3 text-gray-600 leading-7">
                        Katalog produk membantu melihat jenis material yang ada.
                    </p>
                </div>

                <div class="rounded-[28px] border border-gray-200 p-8 bg-white">
                    <div class="text-sm text-gray-500">Informasi Dasar</div>
                    <div class="text-2xl font-bold mt-2">Brand, Variant, Unit</div>
                    <p class="mt-3 text-gray-600 leading-7">
                        Informasi inti produk ditampilkan agar pelanggan lebih mudah memahami pilihan bahan.
                    </p>
                </div>

                <div class="rounded-[28px] border border-gray-200 p-8 bg-slate-900 text-white">
                    <div class="text-sm text-slate-300">Estimasi Harga</div>
                    <div class="text-2xl font-bold mt-2">Lebih Mudah Membandingkan</div>
                    <p class="mt-3 text-slate-300 leading-7">
                        Estimasi harga membantu memberikan gambaran awal terhadap kebutuhan budget proyek.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CATEGORIES STRIP --}}
    <section class="py-8 bg-slate-50 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-[0.18em]">
                    Kategori:
                </span>

                @forelse($categories as $category)
                    <span class="inline-flex px-4 py-2 rounded-full bg-white border border-gray-200 text-slate-700 text-sm font-medium hover:shadow-sm transition">
                        {{ $category }}
                    </span>
                @empty
                    <span class="text-sm text-gray-500">Belum ada kategori.</span>
                @endforelse
            </div>
        </div>
    </section>

    {{-- PRODUCT GRID --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
                <div class="max-w-3xl">
                    <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Katalog Produk</div>
                    <p class="text-gray-600 mt-4 leading-7">
                        Berikut merupakan data material yang dapat
                        dijadikan referensi awal untuk kebutuhan interior dan proyek.
                    </p>
                </div>

                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-slate-900 text-white px-5 py-3 font-medium hover:bg-slate-800 transition">
                    Konsultasi Produk
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($materials as $material)
                    <div class="group rounded-[30px] border border-gray-200 bg-white overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition duration-300">
                        <div class="relative h-44 bg-gradient-to-br from-slate-950 via-slate-800 to-slate-700 p-6 text-white">
                            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,white,transparent_40%)]"></div>

                            <div class="relative flex items-start justify-between gap-4">
                                <span class="inline-flex px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs font-semibold">
                                    {{ $material->category }}
                                </span>

                                <span class="inline-flex px-3 py-1 rounded-full bg-green-400/20 text-green-200 text-xs font-semibold border border-green-300/20">
                                    Active
                                </span>
                            </div>

                            <div class="relative mt-8">
                                <div class="text-sm text-slate-300">Material</div>
                                <h3 class="text-2xl font-bold mt-2 leading-tight">
                                    {{ $material->name }}
                                </h3>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Brand</span>
                                    <span class="font-medium text-slate-900">{{ $material->brand ?? '-' }}</span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Variant</span>
                                    <span class="font-medium text-slate-900 text-right">{{ $material->variant ?? '-' }}</span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Unit</span>
                                    <span class="font-medium text-slate-900">{{ $material->unit }}</span>
                                </div>
                            </div>

                            <div class="mt-6 pt-5 border-t border-gray-200 flex items-end justify-between gap-4">
                                <div>
                                    <div class="text-xs text-gray-500">Estimasi Harga</div>
                                    <div class="text-2xl font-bold text-slate-900">
                                        Rp {{ number_format($material->price_estimate ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>

                                <a href="{{ route('contact') }}"
                                   class="inline-flex items-center justify-center rounded-2xl bg-slate-900 text-white px-4 py-2.5 text-sm font-semibold hover:bg-slate-800 transition">
                                    Hubungi
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-[30px] border border-gray-200 bg-white p-12 text-center">
                        <div class="text-2xl font-bold text-slate-900">Belum ada produk aktif</div>
                        <p class="text-gray-500 mt-3">
                            Data material aktif belum tersedia untuk ditampilkan pada katalog.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10 bg-white rounded-[24px] border border-gray-200 shadow-sm p-4">
                {{ $materials->links() }}
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
                        <div class="text-sm uppercase tracking-[0.2em] text-slate-400 font-semibold">Butuh bantuan memilih?</div>
                        <h2 class="text-3xl md:text-4xl font-bold mt-3 leading-tight">
                            Konsultasikan kebutuhan material interior Anda
                        </h2>
                        <p class="text-slate-300 mt-4 leading-7">
                            Hubungi Velincia HPL untuk mendapatkan informasi lebih lanjut mengenai produk,
                            estimasi awal, dan material yang sesuai dengan kebutuhan proyek Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white text-slate-900 px-6 py-3.5 font-semibold hover:bg-slate-100 transition">
                            Hubungi Kami
                        </a>

                        <a href="{{ route('about') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/15 text-white px-6 py-3.5 font-semibold hover:bg-white/10 transition">
                            Tentang Velincia HPL
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection