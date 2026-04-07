@extends('layouts.company')

@php
    $title = 'Velincia HPL | Tentang';
@endphp

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.12),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.08),transparent_28%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:64px_64px] opacity-20"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-end">
                <div class="lg:col-span-8">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-sm text-slate-200 mb-6">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        About Velincia HPL
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight">
                        Tumbuh sebagai supplier material interior
                        <span class="text-slate-300 block mt-1">yang relevan, praktis, dan berkembang</span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-300 leading-8 max-w-3xl">
                        Velincia HPL hadir untuk membantu kebutuhan material interior anda
                    </p>
                </div>

                <div class="lg:col-span-4">
                    <div class="rounded-[30px] border border-white/10 bg-white/5 backdrop-blur p-6">
                        <div class="text-sm text-slate-300">Fokus Velincia HPL</div>

                        <div class="mt-5 space-y-3">
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <div class="text-sm text-slate-400">Kategori</div>
                                <div class="font-semibold mt-1">Material Interior</div>
                            </div>

                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <div class="text-sm text-slate-400">Target Pengguna</div>
                                <div class="font-semibold mt-1">Pelanggan & Contractor</div>
                            </div>

                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <div class="text-sm text-slate-400">Arah Pengembangan</div>
                                <div class="font-semibold mt-1">Digital & Modern</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STORY --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <div class="lg:col-span-5">
                <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Cerita Kami</div>
                <h2 class="text-3xl md:text-4xl font-bold mt-3 leading-tight">
                    Dibangun dari kebutuhan nyata toko bahan dan proyek interior
                </h2>
            </div>

            <div class="lg:col-span-7 space-y-6 text-gray-600 leading-8">
                <p>
                    Velincia HPL berfokus pada penyediaan berbagai material interior seperti HPL, triplek,
                    edging, handle, wallpanel, vinyl lantai, dan aksesoris lainnya yang dibutuhkan untuk
                    rumah, renovasi, dan kebutuhan proyek contractor.
                </p>

                <p>
                    Tujuan kami bukan hanya menyediakan produk, tetapi juga membantu pelanggan mendapatkan
                    alur pemilihan material yang lebih praktis, lebih jelas, dan lebih sesuai dengan kebutuhan proyek.
                </p>

                <p>
                    Seiring perkembangan kebutuhan bisnis, Velincia HPL juga bergerak menuju transformasi digital
                    agar proses pengelolaan material, estimasi, pemesanan, dan monitoring order menjadi lebih rapi,
                    modern, dan efisien.
                </p>
            </div>
        </div>
    </section>

    {{-- VALUES --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-10">
                <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Nilai Utama</div>
                <h2 class="text-3xl md:text-4xl font-bold mt-3">Prinsip yang membentuk arah Velincia HPL</h2>
                <p class="text-gray-600 mt-4 leading-7">
                    Kami ingin tumbuh bukan hanya sebagai toko material, tetapi juga sebagai bisnis yang relevan,
                    praktis, dan terus berkembang mengikuti kebutuhan pelanggan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="rounded-[30px] border border-gray-200 bg-white p-7">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold">1</div>
                    <div class="text-xl font-bold mt-5">Praktis</div>
                    <p class="text-gray-600 mt-3 leading-7">
                        Mengutamakan alur yang mudah dipahami untuk kebutuhan pembelian material.
                    </p>
                </div>

                <div class="rounded-[30px] border border-gray-200 bg-white p-7">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold">2</div>
                    <div class="text-xl font-bold mt-5">Relevan</div>
                    <p class="text-gray-600 mt-3 leading-7">
                        Menyediakan material yang dekat dengan kebutuhan interior dan proyek lapangan.
                    </p>
                </div>

                <div class="rounded-[30px] border border-gray-200 bg-white p-7">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold">3</div>
                    <div class="text-xl font-bold mt-5">Terstruktur</div>
                    <p class="text-gray-600 mt-3 leading-7">
                        Mendorong pengelolaan produk dan order yang lebih rapi dan mudah dipantau.
                    </p>
                </div>

                <div class="rounded-[30px] border border-gray-200 bg-slate-900 text-white p-7">
                    <div class="w-12 h-12 rounded-2xl bg-white text-slate-900 flex items-center justify-center font-bold">4</div>
                    <div class="text-xl font-bold mt-5">Berkembang</div>
                    <p class="text-slate-300 mt-3 leading-7">
                        Terus bertumbuh menuju sistem digital yang mendukung pelayanan lebih modern.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- VISION & MISSION --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-[32px] border border-gray-200 bg-slate-50 p-8 md:p-10">
                <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Visi</div>
                <h3 class="text-3xl font-bold mt-3">Menjadi supplier material interior yang adaptif dan terpercaya</h3>
                <p class="text-gray-600 mt-5 leading-8">
                    Velincia HPL ingin berkembang sebagai penyedia material interior yang tidak hanya menjual produk,
                    tetapi juga menghadirkan proses yang lebih rapi, relevan, dan mengikuti perkembangan teknologi.
                </p>
            </div>

            <div class="rounded-[32px] border border-gray-200 bg-slate-950 text-white p-8 md:p-10">
                <div class="text-sm uppercase tracking-[0.2em] text-slate-400 font-semibold">Misi</div>
                <h3 class="text-3xl font-bold mt-3">Mendukung kebutuhan material dan proses bisnis yang lebih baik</h3>

                <div class="mt-6 space-y-4 text-slate-300 leading-7">
                    <div>• Menyediakan material interior yang relevan untuk kebutuhan pelanggan dan contractor.</div>
                    <div>• Membantu proses pemilihan dan pemesanan material menjadi lebih praktis.</div>
                    <div>• Mendorong transformasi digital untuk mendukung efisiensi operasional toko.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[36px] bg-slate-900 text-white">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.12),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.08),transparent_28%)]"></div>

                <div class="relative px-8 py-10 md:px-12 md:py-14 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="max-w-2xl">
                        <div class="text-sm uppercase tracking-[0.2em] text-slate-400 font-semibold">Tentang Kami</div>
                        <h2 class="text-3xl md:text-4xl font-bold mt-3 leading-tight">
                            Ingin melihat material yang tersedia di Velincia HPL?
                        </h2>
                        <p class="text-slate-300 mt-4 leading-7">
                            Jelajahi katalog produk kami atau hubungi langsung untuk kebutuhan proyek Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
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
@endsection