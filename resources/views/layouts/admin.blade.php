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
        <aside class="w-64 bg-slate-900 text-white p-6">
            <h1 class="text-xl font-bold mb-6">Velincia Admin</h1>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
                <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Orders</a>
                <a href="{{ route('admin.materials.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Materials</a>
            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" class="mt-8">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded bg-red-600 hover:bg-red-700">
                    Logout
                </button>
            </form>
        </aside>

        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot ?? '' }}

            @yield('content')
        </main>
    </div>
</body>
</html>