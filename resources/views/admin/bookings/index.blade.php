<x-app-layout :role="'admin'" :active="'bookings'" :user="$user" :title="'Kelola Peminjaman'" :headerTitle="'Admin Panel'">
    <div class="animate-slide-up"><h1 class="text-2xl font-bold text-gray-900">Kelola Peminjaman</h1><p class="text-sm text-gray-500 mt-1">Setujui, tolak, atau selesaikan peminjaman</p></div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 animate-slide-up animate-delay-100">
        @foreach([['Total', $stats['total'], 'calendar', 'gray'], ['Pending', $stats['pending'], 'clock', 'yellow'], ['Approved', $stats['approved'], 'check-circle', 'green'], ['Rejected', $stats['rejected'], 'x-circle', 'red'], ['Completed', $stats['completed'], 'calendar-check', 'blue']] as $s)
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm"><div class="flex items-center justify-between mb-1"><p class="text-xs text-gray-500">{{ $s[0] }}</p><i data-lucide="{{ $s[2] }}" class="h-4 w-4 text-{{ $s[3] }}-500"></i></div><p class="text-2xl font-bold text-{{ $s[3] }}-{{ $s[3]==='gray'?'900':'600' }}">{{ $s[1] }}</p></div>
        @endforeach
    </div>

    {{-- Booking Table --}}
    <div class="animate-slide-up animate-delay-200">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Semua Peminjaman</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left"><thead><tr class="border-b border-gray-100">
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Peminjam</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Item</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Tujuan</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="pb-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bookings as $b)
                    @php $bc = match($b->status) { 'PENDING'=>'bg-yellow-100 text-yellow-700','APPROVED','ACTIVE'=>'bg-green-100 text-green-700','COMPLETED'=>'bg-gray-100 text-gray-600','REJECTED'=>'bg-red-100 text-red-600','CANCELLED'=>'bg-gray-100 text-gray-400',default=>'bg-gray-100 text-gray-600' }; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 pr-4"><div><p class="font-medium text-gray-900">{{ $b->user->name }}</p><p class="text-xs text-gray-500">{{ $b->user->email }}</p></div></td>
                        <td class="py-3 pr-4 font-medium text-gray-900">{{ $b->item_name }}</td>
                        <td class="py-3 pr-4 text-gray-500">{{ ucfirst($b->type) }}</td>
                        <td class="py-3 pr-4 text-gray-600 text-xs">{{ $b->start_date->format('d M Y') }} - {{ $b->end_date->format('d M Y') }}</td>
                        <td class="py-3 pr-4 text-gray-500 text-xs max-w-[150px] truncate">{{ $b->purpose }}</td>
                        <td class="py-3 pr-4"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $bc }}">{{ ucfirst(strtolower($b->status)) }}</span></td>
                        <td class="py-3">
                            @if($b->status === 'PENDING')
                            <div class="flex items-center gap-1">
                                <form method="POST" action="{{ route('admin.bookings.status', $b) }}">@csrf @method('PATCH') <input type="hidden" name="status" value="APPROVED"><button class="text-xs px-2 py-1 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-medium">Setujui</button></form>
                                <form method="POST" action="{{ route('admin.bookings.status', $b) }}" x-data>@csrf @method('PATCH') <input type="hidden" name="status" value="REJECTED"><input type="hidden" name="rejection_reason" :value="$el.querySelector('[name=rejection_reason]')?.value"><button class="text-xs px-2 py-1 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 font-medium">Tolak</button></form>
                            </div>
                            @elseif($b->status === 'APPROVED')
                            <form method="POST" action="{{ route('admin.bookings.status', $b) }}">@csrf @method('PATCH') <input type="hidden" name="status" value="COMPLETED"><button class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 font-medium">Selesai</button></form>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-12 text-center text-gray-400">Belum ada peminjaman</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</x-app-layout>
