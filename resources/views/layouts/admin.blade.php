<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Velincia HPL' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-900 text-white p-6 flex flex-col justify-between">
            <div>
                <div class="mb-8">
                    <h1 class="text-xl font-bold">Velincia Admin</h1>
                    <p class="text-sm text-slate-300 mt-1">Panel internal toko</p>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                       class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.orders.*') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
                        Orders
                    </a>

                    <a href="{{ route('admin.materials.index') }}"
                       class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.materials.*') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
                        Materials
                    </a>
                </nav>
            </div>

            <div>
                @auth
                    <div class="mb-4 text-sm text-slate-300">
                        Login sebagai:
                        <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
                    </div>
                @endauth

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="w-full text-left px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                    <div class="font-semibold mb-2">Ada input yang perlu diperbaiki:</div>
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>