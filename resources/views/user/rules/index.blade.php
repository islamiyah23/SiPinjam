<x-app-layout :role="'user'" :active="'rules'" :user="$user" :title="'Tata Tertib'">
    <div class="animate-slide-up"><h1 class="text-2xl font-bold text-gray-900">Tata Tertib Peminjaman</h1><p class="text-sm text-gray-500 mt-1">Peraturan dan ketentuan penggunaan fasilitas</p></div>
    <div class="space-y-4 animate-slide-up animate-delay-100">
        @forelse($rules as $rule)
        <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <button @click="open = !open" class="w-full flex items-center justify-between p-4 sm:p-6 text-left hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm flex-shrink-0">{{ $rule->order }}</div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $rule->title }}</h3>
                </div>
                <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400 transition-transform" :class="open && 'rotate-180'"></i>
            </button>
            <div x-show="open" x-collapse>
                <div class="px-4 pb-4 sm:px-6 sm:pb-6 pt-0"><p class="text-sm text-gray-600 leading-relaxed pl-11">{{ $rule->content }}</p></div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400"><i data-lucide="book-open" class="h-12 w-12 mx-auto mb-3 opacity-30"></i><p>Belum ada tata tertib</p></div>
        @endforelse
    </div>
</x-app-layout>
