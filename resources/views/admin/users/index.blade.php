<x-app-layout :role="'admin'" :active="'users'" :user="$user" :title="'Kelola Pengguna'" :headerTitle="'Admin Panel'">
    <div class="flex items-start justify-between animate-slide-up">
        <div><h1 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h1><p class="text-sm text-gray-500 mt-1">Tambah, edit, dan kelola akun pengguna</p></div>
        <button @click="$refs.userModal.showModal(); isEdit=false; formData={name:'',email:'',role:'user',password:''}" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg inline-flex items-center gap-2 shadow-sm transition-colors"><i data-lucide="plus" class="h-4 w-4"></i> Tambah User</button>
    </div>

    <div class="animate-slide-up animate-delay-100" x-data="{ isEdit: false, editId: '', formData: {} }">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left"><thead><tr class="border-b border-gray-100">
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="pb-3 pr-4 text-xs font-semibold text-gray-500 uppercase">Peminjaman</th>
                    <th class="pb-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 pr-4"><div class="flex items-center gap-3"><div class="h-8 w-8 rounded-full {{ $u->role === 'admin' ? 'bg-orange-500' : 'bg-blue-500' }} flex items-center justify-center text-white text-xs font-semibold">{{ $u->initials }}</div><span class="font-medium text-gray-900">{{ $u->name }}</span></div></td>
                        <td class="py-3 pr-4 text-gray-600">{{ $u->email }}</td>
                        <td class="py-3 pr-4"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $u->role === 'admin' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">{{ ucfirst($u->role) }}</span></td>
                        <td class="py-3 pr-4"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="py-3 pr-4 text-gray-600">{{ $u->bookings_count }}</td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                <button @click="isEdit=true; editId='{{ $u->id }}'; formData={name:'{{ addslashes($u->name) }}',email:'{{ $u->email }}',role:'{{ $u->role }}'}; $refs.userModal.showModal()" class="text-xs text-orange-600 hover:underline font-medium">Edit</button>
                                @if($u->id !== $user->id)
                                <form method="POST" action="{{ route('admin.users.toggle-status', $u) }}">@csrf @method('PATCH') <input type="hidden" name="is_active" value="{{ $u->is_active ? '0' : '1' }}"><button class="text-xs {{ $u->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline font-medium">{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')<button class="text-xs text-red-600 hover:underline font-medium">Hapus</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-gray-400">Belum ada pengguna</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </div>

        {{-- User Modal --}}
        <dialog x-ref="userModal" class="rounded-xl shadow-2xl p-0 w-full max-w-lg backdrop:bg-black/50">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6"><h3 class="text-lg font-bold text-gray-900" x-text="isEdit ? 'Edit User' : 'Tambah User'"></h3><button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="h-5 w-5"></i></button></div>
                <form :action="isEdit ? '{{ url('admin/users') }}/' + editId : '{{ route('admin.users.store') }}'" method="POST" class="space-y-4">
                    @csrf <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama</label><input type="text" name="name" x-model="formData.name" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label><input type="email" name="email" x-model="formData.email" required class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Role</label><select name="role" x-model="formData.role" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"><option value="user">User</option><option value="admin">Admin</option></select></div>
                    <div x-show="!isEdit"><label class="block text-sm font-medium text-gray-700 mb-1">Password</label><input type="password" name="password" x-model="formData.password" class="w-full rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500" placeholder="Default: password123"></div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700" x-text="isEdit ? 'Simpan' : 'Tambah'"></button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</x-app-layout>
