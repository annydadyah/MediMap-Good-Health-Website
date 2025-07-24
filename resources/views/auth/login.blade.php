<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - SDG 3 Health Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen"
    style="background-image: url('images/background.png'); background-size: cover; background-position: center;">

    <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                {{-- Placeholder for Logo --}}
                <img src="images/logo.png" alt="Logo" class="w-16 h-16">
                <h1 class="text-lg font-semibold text-gray-800">Ksatria Petir</h1>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-center mb-2 text-gray-800">MediMap</h2>
        <h2 class="text-2xl font-bold text-center mb-4 text-gray-800">LOGIN</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" name="password" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

            <div class="text-right mb-4">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Don't have an account?
                    Sign up</a>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Login
            </button>
        </form>
    </div>

</body>

</html>