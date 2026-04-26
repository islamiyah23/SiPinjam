<x-app-layout :role="'user'" :active="'dashboard'" :user="$user" :title="'Dashboard'">
    {{-- Welcome Banner --}}
    <div class="animate-slide-up">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl overflow-hidden relative shadow-sm">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
            <div class="relative p-6 sm:p-8 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="sparkles" class="h-5 w-5 sm:h-6 sm:w-6"></i>
                        <h1 class="text-2xl sm:text-3xl font-bold">Selamat {{ date('H') < 12 ? 'Pagi' : (date('H') < 15 ? 'Siang' : (date('H') < 18 ? 'Sore' : 'Malam')) }}, {{ $user->name }}!</h1>
                    </div>
                    <p class="text-blue-100 text-base sm:text-lg">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-white/90 max-w-2xl mt-2 sm:mt-4 text-sm sm:text-base">Selamat datang di SIPINJAM. Kelola peminjaman ruangan dan barang dengan mudah dan efisien.</p>
                </div>
                <a href="{{ route('user.bookings') }}" class="w-full lg:w-auto bg-white text-blue-600 hover:bg-blue-50 shadow-lg px-6 py-3 rounded-lg font-medium inline-flex items-center justify-center transition-colors">
                    <i data-lucide="calendar" class="mr-2 h-4 w-4 sm:h-5 sm:w-5"></i> Ajukan Peminjaman
                </a>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:gap-6 lg:grid-cols-4">
        @foreach([['Total Peminjaman', $total, 'calendar', 'blue', 'gray-900'], ['Sedang Dipinjam', $active, 'clock', 'green', 'green-600'], ['Menunggu', $pending, 'calendar-check', 'yellow', 'yellow-600'], ['Selesai', $completed, 'check-circle-2', 'gray', 'gray-600']] as $stat)
        <div class="animate-slide-up animate-delay-100 bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">{{ $stat[0] }}</p>
                    <p class="text-3xl font-bold text-{{ $stat[4] }}">{{ $stat[1] }}</p>
                </div>
                <div class="p-3 rounded-xl bg-{{ $stat[3] }}-100 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $stat[2] }}" class="h-6 w-6 text-{{ $stat[3] }}-600"></i>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Tren Peminjaman --}}
        <div class="animate-slide-up animate-delay-200 lg:col-span-2 lg:row-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 h-full shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div><h3 class="text-lg font-semibold text-gray-900">Tren Peminjaman</h3><p class="text-sm text-gray-500">6 bulan terakhir</p></div>
                    <i data-lucide="trending-up" class="h-5 w-5 text-blue-600"></i>
                </div>
                @php $maxB = max(array_column($trendData, 'bookings')) ?: 1; $shades = ['bg-blue-100 hover:bg-blue-200','bg-blue-200 hover:bg-blue-300','bg-blue-300 hover:bg-blue-400','bg-blue-400 hover:bg-blue-500','bg-blue-500 hover:bg-blue-600','bg-blue-600 hover:bg-blue-700']; @endphp
                <div class="h-[250px] flex items-end justify-between space-x-2 pt-4 relative w-full">
                    @foreach($trendData as $i => $d)
                    <div class="w-full {{ $shades[$i] ?? 'bg-blue-400' }} rounded-t-md transition-colors" style="height: {{ max(($d['bookings']/$maxB)*100, 10) }}%"></div>
                    @endforeach
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-2">
                    @foreach($trendData as $d)<span>{{ $d['month'] }}</span>@endforeach
                </div>
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div class="animate-slide-up animate-delay-200 lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('user.rooms') }}" class="h-24 flex flex-col items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white hover:bg-blue-50 hover:border-blue-500 hover:scale-105 transition-all shadow-sm">
                        <i data-lucide="door-open" class="h-8 w-8 text-blue-600"></i><span class="text-sm font-medium">Lihat Ruangan</span>
                    </a>
                    <a href="{{ route('user.equipment') }}" class="h-24 flex flex-col items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white hover:bg-green-50 hover:border-green-500 hover:scale-105 transition-all shadow-sm">
                        <i data-lucide="package" class="h-8 w-8 text-green-600"></i><span class="text-sm font-medium">Lihat Barang</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="animate-slide-up animate-delay-300 lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm h-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                    <a href="{{ route('user.bookings') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua <i data-lucide="arrow-right" class="ml-1 h-4 w-4"></i></a>
                </div>
                <div class="space-y-3">
                    @if($pending > 0)
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                        <div class="p-2 rounded-lg bg-yellow-100"><i data-lucide="clock" class="h-4 w-4 text-yellow-600"></i></div>
                        <div class="flex-1"><p class="text-sm font-medium text-gray-900">Menunggu Persetujuan</p><p class="text-xs text-gray-500">{{ $pending }} peminjaman</p></div>
                    </div>
                    @endif
                    @if($active > 0)
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 border border-green-200">
                        <div class="p-2 rounded-lg bg-green-100"><i data-lucide="check-circle-2" class="h-4 w-4 text-green-600"></i></div>
                        <div class="flex-1"><p class="text-sm font-medium text-gray-900">Sedang Dipinjam</p><p class="text-xs text-gray-500">{{ $active }} item aktif</p></div>
                    </div>
                    @endif
                    @if($total == 0)
                    <div class="text-center py-8 text-gray-500"><i data-lucide="calendar" class="h-12 w-12 mx-auto mb-2 opacity-50"></i><p class="text-sm">Belum ada peminjaman</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>