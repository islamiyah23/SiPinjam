<x-app-layout :role="'admin'" :active="'equipment'" :user="$user" :title="'Kelola Barang'" :headerTitle="'Admin Panel'">
    <div class="flex items-start justify-between animate-slide-up">
        <div><h1 class="text-2xl font-bold text-gray-900">Kelola Barang</h1><p class="text-sm text-gray-500 mt-1">Tambah, edit, dan hapus barang inventaris</p></div>
        <button @click="$refs.eqModal.showModal(); isEdit=false; formData={name:'',category:'',quantity:'',available:'',description:''}" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg inline-flex items-center gap-2 shadow-sm transition-colors"><i data-lucide="plus" class="h-4 w-4"></i> Tambah Barang</button>
    </div>

    <div class="animate-slide-up animate-delay-100" x-data="{ isEdit: false, editId: '', formData: {} }">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left"><thead><tr class="border-b border-gray-100">
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Tersedia</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                    <th class="pb-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($equipment as $eq)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 pr-4 font-medium text-gray-900">{{ $eq->name }}</td>
                        <td class="py-3 pr-4"><span class="text-xs font-medium px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">{{ $eq->category ?? '-' }}</span></td>
                        <td class="py-3 pr-4 text-gray-600">{{ $eq->quantity }}</td>
                        <td class="py-3 pr-4"><span class="font-semibold {{ $eq->available > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $eq->available }}</span></td>
                        <td class="py-3 pr-4 text-gray-500 text-xs max-w-[200px] truncate">{{ $eq->description ?? '-' }}</td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                <button @click="isEdit=true; editId='{{ $eq->id }}'; formData={name:'{{ addslashes($eq->name) }}',category:'{{ addslashes($eq->category) }}',quantity:'{{ $eq->quantity }}',available:'{{ $eq->available }}',description:'{{ addslashes($eq->description) }}'}; $refs.eqModal.showModal()" class="text-xs text-orange-600 hover:underline font-medium">Edit</button>
                                <form method="POST" action="{{ route('admin.equipment.destroy', $eq) }}" onsubmit="return confirm('Hapus barang ini?')">@csrf @method('DELETE')<button class="text-xs text-red-600 hover:underline font-medium">Hapus</button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-gray-400">Belum ada barang</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </div>

        {{-- Equipment Modal --}}
        <dialog x-ref="eqModal" class="rounded-xl shadow-2xl p-0 w-full max-w-lg backdrop:bg-black/50">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6"><h3 class="text-lg font-bold text-gray-900" x-text="isEdit ? 'Edit Barang' : 'Tambah Barang'"></h3><button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="h-5 w-5"></i></button></div>
                <form :action="isEdit ? '{{ url('admin/equipment') }}/' + editId : '{{ route('admin.equipment.store') }}'" method="POST" class="space-y-4">
                    @csrf <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama</label><input type="text" name="name" x-model="formData.name" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label><input type="text" name="category" x-model="formData.category" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Stok Total</label><input type="number" name="quantity" x-model="formData.quantity" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Tersedia</label><input type="number" name="available" x-model="formData.available" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label><textarea name="description" x-model="formData.description" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></textarea></div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700" x-text="isEdit ? 'Simpan' : 'Tambah'"></button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</x-app-layout>
