<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediMap</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    @yield('head')

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body class="bg-blue-50">

    {{-- Mobile Navbar --}}
    <nav class="bg-white px-4 py-3 flex justify-between items-center shadow fixed top-0 left-0 right-0 z-20 md:hidden">
        <button id="mobile-menu-button" class="text-gray-700 focus:outline-none text-xl">
            <i class="fas fa-bars"></i>
        </button>
        <span class="text-gray-800 font-bold">MediMap</span>
        @auth
            <a href="{{ route('profile.show') }}" class="text-gray-700">
                <i class="fas fa-user-circle text-2xl"></i>
            </a>
        @endauth
    </nav>

    {{-- Sidebar --}}
    <div id="sidebar"
        class="fixed top-0 left-0 w-64 h-full bg-white border-r z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12">
            <h3 class="text-lg font-bold text-blue-600">MediMap</h3>
        </div>

        <nav class="mt-4 space-y-2 px-4">
            <a href="{{ route('dashboard.index') }}" class="block text-gray-700 hover:bg-blue-100 rounded px-4 py-2">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="{{ route('reviews.index') }}" class="block text-gray-700 hover:bg-blue-100 rounded px-4 py-2">
                <i class="fas fa-chart-bar mr-2"></i> Review
            </a>


            <a href="{{ route('healthfacilities.index') }}"
                class="block text-gray-700 hover:bg-blue-100 rounded px-4 py-2">
                <i class="fas fa-hospital mr-2"></i> Health Facilities
            </a>
        </nav>
    </div>

    {{-- Desktop Navbar --}}
    <nav class="hidden md:flex items-center justify-between px-6 py-4 bg-white shadow fixed top-0 left-64 right-0 z-10">
        <div class="text-xl font-semibold text-gray-800">
            <a href="{{ route('dashboard.index') }}">MediMap</a>
        </div>

        <div class="flex items-center space-x-4">
            @auth
                <div class="relative">
                    <button id="profileDropdownButton" class="flex items-center space-x-2 focus:outline-none">
                        <i class="fas fa-user-circle text-2xl text-gray-600"></i>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div id="profileDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-md hidden z-20 transition-all duration-200">
                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:underline">Login</a>
                <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:underline">Register</a>
            @endauth
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="pt-16 md:pt-24 md:ml-64 px-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center text-sm text-blue-800 py-4 bg-blue-100 mt-auto">
        &copy; {{ date('Y') }} <a href="https://itpln.ac.id/" class="text-blue-800 hover:underline">Ksatria Petir - IT
            PLN</a>
    </footer>

    {{-- Scripts --}}
    <script>
        // Sidebar toggle (mobile)
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('mobile-menu-button');
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Profile dropdown toggle (desktop)
        const dropdownBtn = document.getElementById('profileDropdownButton');
        const dropdownMenu = document.getElementById('profileDropdown');

        dropdownBtn?.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdownMenu?.classList.toggle('hidden');
        });

        // Close dropdown when click outside
        window.addEventListener('click', function () {
            if (!dropdownMenu?.classList.contains('hidden')) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @stack('scripts')
</body>

</html>