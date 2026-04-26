<x-app-layout :role="'user'" :active="'equipment'" :user="$user" :title="'Barang'">
    <div class="animate-slide-up"><h1 class="text-2xl font-bold text-gray-900">Daftar Barang</h1><p class="text-sm text-gray-500 mt-1">Lihat barang yang tersedia untuk dipinjam</p></div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 animate-slide-up animate-delay-100">
        @forelse($equipment as $eq)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4"><h3 class="text-lg font-bold text-white">{{ $eq->name }}</h3><p class="text-green-100 text-sm mt-1">{{ $eq->category ?? 'Umum' }}</p></div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Stok Total</span><span class="font-semibold text-gray-900">{{ $eq->quantity }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Tersedia</span>
                    <span class="font-semibold {{ $eq->available > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $eq->available }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-green-500 h-2 rounded-full" style="width: {{ $eq->quantity > 0 ? ($eq->available / $eq->quantity) * 100 : 0 }}%"></div></div>
                @if($eq->description)<p class="text-sm text-gray-500">{{ $eq->description }}</p>@endif
                <a href="{{ route('user.bookings') }}" class="w-full mt-2 flex items-center justify-center gap-2 {{ $eq->available > 0 ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }} px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i data-lucide="package" class="h-4 w-4"></i> {{ $eq->available > 0 ? 'Pinjam Barang' : 'Stok Habis' }}
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-gray-400"><i data-lucide="package" class="h-12 w-12 mx-auto mb-3 opacity-30"></i><p>Belum ada barang tersedia</p></div>
        @endforelse
    </div>
</x-app-layout>
