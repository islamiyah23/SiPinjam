@props(['role' => 'user', 'active' => '', 'user' => null, 'title' => 'SIPINJAM', 'headerTitle' => null])
@php $user = $user ?? auth()->user(); $headerTitle = $headerTitle ?? ($role === 'admin' ? 'Admin Panel' : 'User Portal'); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'SIPINJAM' }} - SIPINJAM</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            {{-- Sidebar --}}
            @include('layouts.sidebar', ['role' => $role ?? 'user', 'active' => $active ?? '', 'user' => $user ?? auth()->user()])

            {{-- Main Content --}}
            <main class="flex-1 flex flex-col h-screen overflow-y-auto lg:ml-64 bg-gray-50 transition-all duration-300">

                {{-- Header --}}
                <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b bg-white/95 backdrop-blur px-4 lg:px-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $headerTitle }}</h2>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>
                </header>

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mx-4 mt-4 lg:mx-6 animate-slide-down">
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm" x-data="{ show: true }" x-show="show">
                            <i data-lucide="check-circle" class="h-4 w-4 flex-shrink-0"></i>
                            {{ session('success') }}
                            <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mx-4 mt-4 lg:mx-6">
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Page Content --}}
                <div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8 animate-fade-in">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <footer class="mt-auto px-6 py-4 border-t border-gray-200 text-xs text-gray-500 flex justify-between bg-white">
                    <span>© 2026 SIPINJAM - Sistem Informasi Peminjaman</span>
                    <span>Built with Laravel</span>
                </footer>
            </main>
        </div>

        <script>lucide.createIcons();</script>
    </body>
</html>
