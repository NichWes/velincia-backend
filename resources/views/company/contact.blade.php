@extends('layouts.company')

@php
    $title = 'Velincia HPL | Kontak';
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
                        Contact Velincia HPL
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight">
                        Hubungi Velincia HPL
                        <span class="text-slate-300 block mt-1">untuk pertanyaan produk dan kebutuhan proyek interior</span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-300 leading-8 max-w-3xl">
                        Jika Anda membutuhkan informasi produk, estimasi awal, atau ingin berdiskusi mengenai
                        kebutuhan material, silakan hubungi kami melalui informasi kontak berikut.
                    </p>
                </div>

                <div class="lg:col-span-4">
                    <div class="rounded-[30px] border border-white/10 bg-white/5 backdrop-blur p-6">
                        <div class="text-sm text-slate-300">Jam Operasional</div>
                        <div class="text-3xl font-bold mt-2">Senin - Sabtu</div>
                        <div class="text-slate-300 mt-1">08.00 - 17.00</div>

                        <div class="mt-6 rounded-2xl bg-white/5 border border-white/10 p-4">
                            <div class="text-sm text-slate-400">Lokasi</div>
                            <div class="font-semibold mt-1">Komplek Ruko Casa Gardenia, Jl. Selang Bulak Blok R1 No.12, dan No.14, Wanasari, Kec. Cibitung, Kabupaten Bekasi, Jawa Barat 17520</div>
                        </div>

                        <a href="https://wa.me/6282124636876"
                           target="_blank"
                           class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-white text-slate-900 px-5 py-3.5 font-semibold hover:bg-slate-100 transition">
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT INFO --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5 rounded-[32px] border border-gray-200 bg-slate-50 p-8 md:p-10">
                <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Informasi Kontak</div>
                <h2 class="text-3xl font-bold mt-3">Velincia HPL</h2>

                <div class="mt-8 space-y-6 text-gray-700">
                    <div>
                        <div class="text-sm text-gray-500">Nama Toko</div>
                        <div class="text-2xl font-semibold mt-1">Velincia HPL Cibitung</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Lokasi</div>
                        <div class="text-xl font-medium mt-1">Komplek Ruko Casa Gardenia, Jl. Selang Bulak Blok R1 No.12, dan No.14, Wanasari, Kec. Cibitung, Kabupaten Bekasi, Jawa Barat 17520</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Jam Operasional</div>
                        <div class="text-xl font-medium mt-1">Senin - Sabtu, 08.00 - 17.00</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Kontak Cepat</div>
                        <div class="text-xl font-medium mt-1">0821-2464-6876</div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="https://wa.me/6282124636876"
                       target="_blank"
                       class="inline-flex items-center justify-center rounded-2xl bg-green-600 text-white px-6 py-3.5 font-semibold hover:bg-green-700 transition">
                        Chat WhatsApp
                    </a>

                    <a href="{{ route('products') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-slate-900 text-white px-6 py-3.5 font-semibold hover:bg-slate-800 transition">
                        Lihat Produk
                    </a>
                </div>

                <div space-y-6></div>
                
                {{-- GOOGLE MAP --}}
                <section class="pb-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="mb-8">
                            <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">
                            </div>
                        </div>

                        <div class="rounded-[28px] overflow-hidden border border-gray-200 shadow-sm">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.174154261459!2d107.09205807355463!3d-6.240764261107725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698f0cfcc642bb%3A0x1d2216b6d7d21f9d!2sToko%20HPL%20Cibitung%20%7C%20Velincia%20HPL!5e0!3m2!1sid!2sid!4v1775232990354!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">"
                                width="100%"
                                height="180"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full">
                            </iframe>
                        </div>

                    </div>
                </section>
            </div>


            <div class="lg:col-span-7 space-y-6">
                <div class="rounded-[32px] border border-gray-200 bg-white p-8 md:p-10">
                    <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Catatan Layanan</div>
                    <h2 class="text-3xl font-bold mt-3">Apa yang bisa Anda tanyakan ke Velincia HPL?</h2>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-[24px] border border-gray-200 bg-slate-950 text-white p-6">
                            <div class="text-lg font-bold">Produk Interior</div>
                            <p class="text-slate-300 mt-3 leading-7">
                                HPL, triplek, edging, handle, wallpanel, vinyl lantai, dan aksesoris lainnya.
                            </p>
                        </div>

                        <div class="rounded-[24px] border border-gray-200 bg-slate-50 p-6">
                            <div class="text-lg font-bold">Kebutuhan Proyek</div>
                            <p class="text-gray-600 mt-3 leading-7">
                                Cocok untuk kebutuhan rumah tinggal, renovasi, hingga proyek contractor.
                            </p>
                        </div>

                        <div class="rounded-[24px] border border-gray-200 bg-slate-50 p-6">
                            <div class="text-lg font-bold">Estimasi Awal</div>
                            <p class="text-gray-600 mt-3 leading-7">
                                Membantu memberikan gambaran awal terkait material dan budget proyek.
                            </p>
                        </div>

                        <div class="rounded-[24px] border border-gray-200 bg-slate-50 p-6">
                            <div class="text-lg font-bold">Arah Digital</div>
                            <p class="text-gray-600 mt-3 leading-7">
                                Velincia HPL sedang mengembangkan sistem digital untuk alur bisnis yang lebih modern.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[32px] border border-gray-200 bg-slate-50 p-8 md:p-10">
                    <div class="text-sm uppercase tracking-[0.2em] text-slate-500 font-semibold">Saran Saat Menghubungi</div>
                    <h3 class="text-2xl font-bold mt-3">Agar diskusi lebih cepat dan jelas</h3>

                    <div class="mt-6 space-y-4 text-gray-600 leading-7">
                        <div>• Siapkan jenis material yang dibutuhkan.</div>
                        <div>• Sertakan perkiraan jumlah atau ukuran kebutuhan.</div>
                        <div>• Jelaskan lokasi proyek atau kebutuhan penggunaan material.</div>
                        <div>• Tanyakan kategori produk yang paling sesuai untuk kebutuhan Anda.</div>
                    </div>
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
                        <div class="text-sm uppercase tracking-[0.2em] text-slate-400 font-semibold">Butuh material?</div>
                        <h2 class="text-3xl md:text-4xl font-bold mt-3 leading-tight">
                            Jelajahi produk Velincia HPL atau hubungi kami sekarang
                        </h2>
                        <p class="text-slate-300 mt-4 leading-7">
                            Kami siap membantu memberikan informasi produk dan arahan kebutuhan material yang sesuai.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white text-slate-900 px-6 py-3.5 font-semibold hover:bg-slate-100 transition">
                            Jelajahi Produk
                        </a>

                        <a href="https://wa.me/6282124636876"
                           target="_blank"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/15 text-white px-6 py-3.5 font-semibold hover:bg-white/10 transition">
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection