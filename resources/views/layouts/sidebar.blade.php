{{-- 
    Shared Sidebar Component
    Usage: @include('layouts.sidebar', ['role' => 'user', 'active' => 'dashboard'])
    
    Props:
    - $role: 'user' | 'admin'
    - $active: current active menu key
    - $user: authenticated user object
--}}

@php
    $isAdmin = $role === 'admin';

    // Gradient colors based on role
    $gradient = $isAdmin
        ? 'from-[#9a3412] to-[#f97316]'
        : 'from-[#1e3a8a] to-[#3b82f6]';

    $logoBg = $isAdmin ? 'bg-white/25' : 'bg-orange-500';
    $avatarBg = $isAdmin ? 'bg-orange-400' : 'bg-blue-500';

    // Navigation items
    $navItems = $isAdmin
        ? [
            ['key' => 'dashboard',  'label' => 'Dashboard',         'icon' => 'home',       'route' => 'admin.dashboard'],
            ['key' => 'bookings',   'label' => 'Kelola Peminjaman', 'icon' => 'calendar',   'route' => 'admin.bookings.index'],
            ['key' => 'rooms',      'label' => 'Kelola Ruangan',    'icon' => 'door-open',  'route' => 'admin.rooms.index'],
            ['key' => 'equipment',  'label' => 'Kelola Barang',     'icon' => 'package',    'route' => 'admin.equipment.index'],
            ['key' => 'users',      'label' => 'Kelola Pengguna',   'icon' => 'users',      'route' => 'admin.users.index'],
        ]
        : [
            ['key' => 'dashboard',  'label' => 'Dashboard',          'icon' => 'home',       'route' => 'user.dashboard'],
            ['key' => 'bookings',   'label' => 'Riwayat Peminjaman', 'icon' => 'calendar',   'route' => 'user.bookings'],
            ['key' => 'rooms',      'label' => 'Ruangan',            'icon' => 'door-open',  'route' => 'user.rooms'],
            ['key' => 'equipment',  'label' => 'Barang',             'icon' => 'package',    'route' => 'user.equipment'],
            ['key' => 'rules',      'label' => 'Tata Tertib',        'icon' => 'book-open',  'route' => 'user.rules'],
        ];
@endphp

{{-- Desktop Sidebar --}}
<aside class="fixed left-0 top-0 z-40 h-screen w-64 bg-gradient-to-b {{ $gradient }} text-white transition-all duration-300 flex-col hidden lg:flex">

    {{-- Logo --}}
    <div class="flex h-16 items-center gap-3 px-6 flex-shrink-0">
        <div class="{{ $logoBg }} p-2 rounded-lg">
            <i data-lucide="box" class="w-5 h-5 text-white"></i>
        </div>
        <h1 class="text-2xl font-bold tracking-tight">SIPINJAM</h1>
    </div>

    {{-- User Info --}}
    <div class="px-4 pb-4 flex-shrink-0">
        <div class="flex items-center gap-3 rounded-lg px-3 py-2.5">
            <div class="h-10 w-10 rounded-full {{ $avatarBg }} border-2 border-white/30 flex items-center justify-center font-semibold text-white text-sm">
                {{ $user->initials }}
            </div>
            <div class="flex-1 text-left min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ $user->name }}</p>
                <p class="text-xs text-white/70 truncate">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 px-3 py-2 overflow-y-auto">
        @foreach($navItems as $item)
            @php
                $isActive = ($active ?? '') === $item['key'];
                $linkClass = $isActive
                    ? 'bg-white/20 text-white shadow-sm scale-105'
                    : 'text-white/80 hover:bg-white/10 hover:text-white hover:scale-105';
            @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $linkClass }} transition-all duration-200">
                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 flex-shrink-0"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="border-t border-white/10 p-4 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-colors">
                <i data-lucide="log-out" class="h-5 w-5"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="sidebarOpen" x-cloak
     class="fixed inset-0 z-50 lg:hidden"
     x-transition:enter="transition-opacity duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" @click="sidebarOpen = false"></div>

    {{-- Slide-in panel --}}
    <div class="absolute left-0 top-0 h-full w-64 bg-gradient-to-b {{ $gradient }} text-white flex flex-col"
         x-show="sidebarOpen"
         x-transition:enter="transition-transform duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition-transform duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">

        {{-- Logo --}}
        <div class="flex h-16 items-center justify-between px-6 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="{{ $logoBg }} p-2 rounded-lg">
                    <i data-lucide="box" class="w-5 h-5 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">SIPINJAM</h1>
            </div>
            <button @click="sidebarOpen = false" class="p-1 text-white/80 hover:text-white">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        {{-- User Info --}}
        <div class="px-4 pb-4 flex-shrink-0">
            <div class="flex items-center gap-3 rounded-lg px-3 py-2.5">
                <div class="h-10 w-10 rounded-full {{ $avatarBg }} border-2 border-white/30 flex items-center justify-center font-semibold text-white text-sm">
                    {{ $user->initials }}
                </div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $user->name }}</p>
                    <p class="text-xs text-white/70 truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-1 px-3 py-2 overflow-y-auto">
            @foreach($navItems as $item)
                @php
                    $isActive = ($active ?? '') === $item['key'];
                    $linkClass = $isActive
                        ? 'bg-white/20 text-white shadow-sm'
                        : 'text-white/80 hover:bg-white/10 hover:text-white';
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $linkClass }} transition-all duration-200">
                    <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 flex-shrink-0"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="border-t border-white/10 p-4 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-colors">
                    <i data-lucide="log-out" class="h-5 w-5"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
