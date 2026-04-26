<x-app-layout :role="'user'" :active="'bookings'" :user="$user" :title="'Riwayat Peminjaman'">
    {{-- Page Title + CTA --}}
    <div class="flex items-start justify-between animate-slide-up">
        <div><h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1><p class="text-sm text-gray-500 mt-1">Kelola dan lihat riwayat peminjaman Anda</p></div>
        <button @click="$refs.bookingModal.showModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg inline-flex items-center gap-2 shadow-sm transition-colors">
            <i data-lucide="plus" class="h-4 w-4"></i> Peminjaman Baru
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 animate-slide-up animate-delay-100">
        @foreach([['Total Peminjaman', $stats['total'], 'calendar', 'gray'], ['Menunggu', $stats['pending'], 'clock', 'yellow'], ['Disetujui', $stats['approved'], 'trending-up', 'green'], ['Selesai', $stats['completed'], 'calendar-check', 'blue']] as $s)
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2"><p class="text-sm text-gray-500">{{ $s[0] }}</p><i data-lucide="{{ $s[2] }}" class="h-5 w-5 text-{{ $s[3] }}-500"></i></div>
            <p class="text-3xl font-bold text-{{ $s[3] }}-{{ $s[3] === 'gray' ? '900' : '500' }}">{{ $s[1] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Daftar Peminjaman --}}
    <div class="animate-slide-up animate-delay-200" x-data="{ view: 'grid' }">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Peminjaman</h3>
                <div class="flex items-center gap-1 border border-gray-200 rounded-lg p-1">
                    <button @click="view='grid'" :class="view==='grid' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-50'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors"><i data-lucide="layout-grid" class="h-4 w-4"></i> Grid</button>
                    <button @click="view='table'" :class="view==='table' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-50'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors"><i data-lucide="list" class="h-4 w-4"></i> Tabel</button>
                </div>
            </div>

            {{-- GRID VIEW --}}
            <div x-show="view==='grid'" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($bookings as $item)
                @php
                    $isRoom = $item->type === 'room';
                    $bgColor = $isRoom ? 'bg-blue-600' : 'bg-green-500';
                    $badgeColor = match($item->status) { 'PENDING'=>'bg-yellow-100 text-yellow-700 border border-yellow-200','APPROVED','ACTIVE'=>'bg-green-100 text-green-700 border border-green-200','COMPLETED'=>'bg-gray-100 text-gray-600 border border-gray-200','REJECTED'=>'bg-red-100 text-red-600 border border-red-200',default=>'bg-gray-100 text-gray-600 border border-gray-200' };
                    $statusLabel = match($item->status) { 'PENDING'=>'Menunggu','APPROVED'=>'Disetujui','ACTIVE'=>'Aktif','COMPLETED'=>'Selesai','REJECTED'=>'Ditolak','CANCELLED'=>'Dibatalkan',default=>$item->status };
                    $statusIcon = match($item->status) { 'PENDING'=>'clock','APPROVED','ACTIVE'=>'check-circle','COMPLETED'=>'check-circle-2','REJECTED'=>'x-circle',default=>'circle' };
                @endphp
                <div class="rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="{{ $bgColor }} px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-white/90 text-sm font-medium"><i data-lucide="{{ $isRoom ? 'map-pin' : 'package' }}" class="h-4 w-4"></i> {{ $isRoom ? 'Ruangan' : 'Barang' }}</div>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $badgeColor }}"><i data-lucide="{{ $statusIcon }}" class="h-3 w-3 inline-block mr-1"></i>{{ $statusLabel }}</span>
                    </div>
                    <div class="{{ $bgColor }} px-4 pb-4"><h4 class="text-xl font-bold text-white">{{ $item->item_name }}</h4></div>
                    <div class="p-4 bg-white space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="calendar" class="h-4 w-4 text-gray-400 flex-shrink-0"></i><div><p class="text-xs text-gray-400">Tanggal</p><p class="font-medium">{{ $item->start_date->format('d M Y') }}</p></div></div>
                        <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="clock" class="h-4 w-4 text-gray-400 flex-shrink-0"></i><div><p class="text-xs text-gray-400">Sampai</p><p class="font-medium">{{ $item->end_date->format('d M Y') }}</p></div></div>
                        @if($item->purpose)<p class="text-sm text-gray-500 pt-1">{{ $item->purpose }}</p>@endif
                    </div>
                </div>
                @empty
                <div class="lg:col-span-3 text-center py-16 text-gray-400"><i data-lucide="calendar" class="h-12 w-12 mx-auto mb-3 opacity-30"></i><p class="text-base font-medium">Belum ada peminjaman</p><p class="text-sm mt-1">Ajukan peminjaman pertama Anda</p></div>
                @endforelse
            </div>

            {{-- TABLE VIEW --}}
            <div x-show="view==='table'" x-cloak class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead><tr class="border-b border-gray-100">
                        <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                        <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe</th>
                        <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                        <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($bookings as $item)
                        @php $badgeColor = match($item->status) { 'PENDING'=>'bg-yellow-100 text-yellow-700','APPROVED','ACTIVE'=>'bg-green-100 text-green-700','COMPLETED'=>'bg-gray-100 text-gray-600','REJECTED'=>'bg-red-100 text-red-600',default=>'bg-gray-100 text-gray-600' }; @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 pr-4 font-medium text-gray-900">{{ $item->item_name }}</td>
                            <td class="py-3 pr-4 text-gray-500">{{ ucfirst($item->type) }}</td>
                            <td class="py-3 pr-4 text-gray-600">{{ $item->start_date->format('d M Y') }}</td>
                            <td class="py-3 pr-4"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $badgeColor }}">{{ ucfirst(strtolower($item->status)) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-12 text-center text-gray-400">Belum ada peminjaman</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Booking Modal --}}
    <dialog x-ref="bookingModal" class="rounded-xl shadow-2xl p-0 w-full max-w-lg backdrop:bg-black/50">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Peminjaman Baru</h3>
                <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="h-5 w-5"></i></button>
            </div>
            <form method="POST" action="{{ route('user.bookings.store') }}" class="space-y-4">
                @csrf
                <div x-data="{ type: 'room' }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="type" x-model="type" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="room">Ruangan</option>
                        <option value="equipment">Barang</option>
                    </select>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Item</label>
                        <select name="item_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <template x-if="type==='room'">
                                <optgroup label="Ruangan">@foreach($rooms as $r)<option value="{{ $r->id }}">{{ $r->name }} ({{ $r->capacity }} orang)</option>@endforeach</optgroup>
                            </template>
                            <template x-if="type==='equipment'">
                                <optgroup label="Barang">@foreach($equipment as $e)<option value="{{ $e->id }}">{{ $e->name }} ({{ $e->available }} tersedia)</option>@endforeach</optgroup>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label><input type="date" name="start_date" required class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label><input type="date" name="end_date" required class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label><textarea name="purpose" rows="3" required class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Jelaskan tujuan peminjaman..."></textarea></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label><input type="text" name="notes" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan..."></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Ajukan</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>