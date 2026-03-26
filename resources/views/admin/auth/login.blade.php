<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>

        @if($errors->any())
            <div class="mb-4 rounded bg-red-100 text-red-700 px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" value="1">
                <label>Remember me</label>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-lg hover:bg-slate-800">
                Login
            </button>
        </form>
    </div>
</body>
</html>