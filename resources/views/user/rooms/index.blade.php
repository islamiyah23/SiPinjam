<x-app-layout :role="'user'" :active="'rooms'" :user="$user" :title="'Ruangan'">
    <div class="animate-slide-up"><h1 class="text-2xl font-bold text-gray-900">Daftar Ruangan</h1><p class="text-sm text-gray-500 mt-1">Lihat ruangan yang tersedia untuk dipinjam</p></div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 animate-slide-up animate-delay-100">
        @forelse($rooms as $room)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4"><h3 class="text-lg font-bold text-white">{{ $room->name }}</h3><p class="text-blue-100 text-sm mt-1">{{ $room->building ?? 'Kampus' }} · Lantai {{ $room->floor ?? '-' }}</p></div>
            <div class="p-4 space-y-3">
                <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="users" class="h-4 w-4 text-gray-400"></i> Kapasitas: <span class="font-semibold">{{ $room->capacity }} orang</span></div>
                @if($room->description)
                <div><p class="text-xs text-gray-400 mb-1.5">Fasilitas</p><div class="flex flex-wrap gap-1.5">@foreach($room->facilities as $f)<span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $f }}</span>@endforeach</div></div>
                @endif
                <a href="{{ route('user.bookings') }}" class="w-full mt-2 flex items-center justify-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"><i data-lucide="calendar" class="h-4 w-4"></i> Pinjam Ruangan</a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-gray-400"><i data-lucide="door-open" class="h-12 w-12 mx-auto mb-3 opacity-30"></i><p>Belum ada ruangan tersedia</p></div>
        @endforelse
    </div>
</x-app-layout>
