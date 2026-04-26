<x-app-layout :role="'admin'" :active="'dashboard'" :user="$user" :title="'Dashboard'" :headerTitle="'Admin Panel'">
    {{-- Welcome Banner --}}
    <div class="animate-slide-up">
        <div class="bg-gradient-to-br from-orange-500 to-orange-700 text-white rounded-xl overflow-hidden relative shadow-sm">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
            <div class="relative p-6 sm:p-8 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2"><i data-lucide="shield" class="h-5 w-5 sm:h-6 sm:w-6"></i><h1 class="text-2xl sm:text-3xl font-bold">Admin Dashboard</h1></div>
                    <p class="text-orange-100 text-base sm:text-lg">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-white/90 max-w-2xl mt-2 text-sm sm:text-base">Kelola peminjaman, ruangan, barang, dan pengguna dari panel administrasi.</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="w-full lg:w-auto bg-white text-orange-600 hover:bg-orange-50 shadow-lg px-6 py-3 rounded-lg font-medium inline-flex items-center justify-center transition-colors"><i data-lucide="calendar" class="mr-2 h-5 w-5"></i> Kelola Peminjaman</a>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid gap-4 sm:gap-6 lg:grid-cols-4">
        @foreach([['Total User', $totalUsers, 'users', 'orange', 'gray-900'], ['Total Peminjaman', $totalBookings, 'calendar', 'blue', 'gray-900'], ['Total Ruangan', $totalRooms, 'door-open', 'green', 'gray-900'], ['Total Barang', $totalEquipment, 'package', 'purple', 'gray-900']] as $s)
        <div class="animate-slide-up animate-delay-100 bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group">
            <div class="flex items-start justify-between"><div class="space-y-2"><p class="text-sm text-gray-500">{{ $s[0] }}</p><p class="text-3xl font-bold text-{{ $s[4] }}">{{ $s[1] }}</p></div><div class="p-3 rounded-xl bg-{{ $s[3] }}-100 group-hover:scale-110 transition-transform"><i data-lucide="{{ $s[2] }}" class="h-6 w-6 text-{{ $s[3] }}-600"></i></div></div>
        </div>
        @endforeach

        {{-- Booking Status Breakdown --}}
        <div class="animate-slide-up animate-delay-200 lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Peminjaman</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([['Pending', $pendingBookings, 'yellow'], ['Approved', $approvedBookings, 'green'], ['Rejected', $rejectedBookings, 'red'], ['Completed', $completedBookings, 'gray']] as $st)
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-{{ $st[2] }}-50 border border-{{ $st[2] }}-200">
                        <div class="w-3 h-3 rounded-full bg-{{ $st[2] }}-500"></div>
                        <div><p class="text-sm font-medium text-gray-900">{{ $st[0] }}</p><p class="text-lg font-bold text-{{ $st[2] }}-600">{{ $st[1] }}</p></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tren Peminjaman --}}
        <div class="animate-slide-up animate-delay-200 lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm h-full">
                <div class="flex items-center justify-between mb-6"><div><h3 class="text-lg font-semibold text-gray-900">Tren Peminjaman</h3><p class="text-sm text-gray-500">6 bulan terakhir</p></div><i data-lucide="trending-up" class="h-5 w-5 text-orange-600"></i></div>
                @php $maxB = max(array_column($trendData, 'bookings')) ?: 1; $shades = ['bg-orange-100 hover:bg-orange-200','bg-orange-200 hover:bg-orange-300','bg-orange-300 hover:bg-orange-400','bg-orange-400 hover:bg-orange-500','bg-orange-500 hover:bg-orange-600','bg-orange-600 hover:bg-orange-700']; @endphp
                <div class="h-[200px] flex items-end justify-between space-x-2 pt-4 w-full">
                    @foreach($trendData as $i => $d)<div class="w-full {{ $shades[$i] ?? 'bg-orange-400' }} rounded-t-md transition-colors" style="height: {{ max(($d['bookings']/$maxB)*100, 10) }}%"></div>@endforeach
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-2">@foreach($trendData as $d)<span>{{ $d['month'] }}</span>@endforeach</div>
            </div>
        </div>

        {{-- Recent Bookings --}}
        <div class="animate-slide-up animate-delay-300 lg:col-span-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">Peminjaman Terbaru</h3><a href="{{ route('admin.bookings.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Lihat Semua →</a></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left"><thead><tr class="border-b border-gray-100"><th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Peminjam</th><th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Item</th><th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Tipe</th><th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th><th class="pb-3 text-xs font-semibold text-gray-500 uppercase">Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentBookings as $b)
                        @php $bc = match($b->status) { 'PENDING'=>'bg-yellow-100 text-yellow-700','APPROVED','ACTIVE'=>'bg-green-100 text-green-700','COMPLETED'=>'bg-gray-100 text-gray-600','REJECTED'=>'bg-red-100 text-red-600',default=>'bg-gray-100 text-gray-600' }; @endphp
                        <tr class="hover:bg-gray-50"><td class="py-3 pr-4 font-medium text-gray-900">{{ $b->user->name }}</td><td class="py-3 pr-4 text-gray-600">{{ $b->item_name }}</td><td class="py-3 pr-4 text-gray-500">{{ ucfirst($b->type) }}</td><td class="py-3 pr-4 text-gray-600">{{ $b->start_date->format('d M Y') }}</td><td class="py-3"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $bc }}">{{ ucfirst(strtolower($b->status)) }}</span></td></tr>
                        @endforeach
                    </tbody></table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
