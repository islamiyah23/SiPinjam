<x-app-layout :role="'admin'" :active="'rooms'" :user="$user" :title="'Kelola Ruangan'" :headerTitle="'Admin Panel'">
    <div class="flex items-start justify-between animate-slide-up">
        <div><h1 class="text-2xl font-bold text-gray-900">Kelola Ruangan</h1><p class="text-sm text-gray-500 mt-1">Tambah, edit, dan hapus ruangan</p></div>
        <button @click="$refs.roomModal.showModal(); isEdit=false; formData={name:'',capacity:'',description:'',building:'',floor:''}" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg inline-flex items-center gap-2 shadow-sm transition-colors"><i data-lucide="plus" class="h-4 w-4"></i> Tambah Ruangan</button>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 animate-slide-up animate-delay-100" x-data="{ isEdit: false, editId: '', formData: {} }">
        @forelse($rooms as $room)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4"><h3 class="text-lg font-bold text-white">{{ $room->name }}</h3><p class="text-orange-100 text-sm mt-1">{{ $room->building ?? 'Kampus' }} · Lantai {{ $room->floor ?? '-' }}</p></div>
            <div class="p-4 space-y-3">
                <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="users" class="h-4 w-4 text-gray-400"></i> Kapasitas: <span class="font-semibold">{{ $room->capacity }} orang</span></div>
                @if($room->description)<p class="text-sm text-gray-500">{{ $room->description }}</p>@endif
                <div class="flex items-center gap-2 pt-2">
                    <button @click="isEdit=true; editId='{{ $room->id }}'; formData={name:'{{ $room->name }}',capacity:'{{ $room->capacity }}',description:'{{ $room->description }}',building:'{{ $room->building }}',floor:'{{ $room->floor }}'}; $refs.roomModal.showModal()" class="flex-1 text-sm font-medium text-orange-600 bg-orange-50 hover:bg-orange-100 px-3 py-2 rounded-lg text-center transition-colors">Edit</button>
                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus ruangan ini?')">@csrf @method('DELETE')<button class="text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition-colors">Hapus</button></form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-gray-400"><i data-lucide="door-open" class="h-12 w-12 mx-auto mb-3 opacity-30"></i><p>Belum ada ruangan</p></div>
        @endforelse

        {{-- Room Modal --}}
        <dialog x-ref="roomModal" class="rounded-xl shadow-2xl p-0 w-full max-w-lg backdrop:bg-black/50">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6"><h3 class="text-lg font-bold text-gray-900" x-text="isEdit ? 'Edit Ruangan' : 'Tambah Ruangan'"></h3><button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="h-5 w-5"></i></button></div>
                <form :action="isEdit ? '{{ url('admin/rooms') }}/' + editId : '{{ route('admin.rooms.store') }}'" method="POST" class="space-y-4">
                    @csrf <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama</label><input type="text" name="name" x-model="formData.name" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas</label><input type="number" name="capacity" x-model="formData.capacity" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Lantai</label><input type="number" name="floor" x-model="formData.floor" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Gedung</label><input type="text" name="building" x-model="formData.building" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Fasilitas</label><textarea name="description" x-model="formData.description" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></textarea></div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700" x-text="isEdit ? 'Simpan' : 'Tambah'"></button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</x-app-layout>
