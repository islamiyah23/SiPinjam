<script setup>
import { computed, ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
// Tambahkan icon Menu (Hamburger) dan X (Close) dari Lucide
import { Home, ClipboardList, DoorOpen, Package, ShieldCheck, CalendarDays, LogOut, User, Menu, X } from '@lucide/vue';

import sidebarLogo from '@images/side bar user.png';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const currentUrl = computed(() => page.url);

// State untuk mengatur buka/tutup sidebar di Mobile
const isSidebarOpen = ref(false);

const navItems = [
    { label: 'Riwayat Peminjaman', icon: ClipboardList, href: '/bookings' },
    { label: 'Ruangan', icon: DoorOpen, href: '/ruangan' },
    { label: 'Barang', icon: Package, href: '/barang' },
    { label: 'Tata Tertib', icon: ShieldCheck, href: '/tata_tertib' },
    { label: 'Kalender Akademik', icon: CalendarDays, href: '/kalender' },
];

const isActive = (href) => currentUrl.value === href || currentUrl.value.startsWith(href + '/');
const logout = () => router.post('/logout');

const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
};

// Fungsi untuk menutup sidebar setiap kali menu diklik (khusus mobile)
const closeSidebar = () => {
    isSidebarOpen.value = false;
};
</script>

<template>
    <div>
        <div class="md:hidden fixed top-0 left-0 right-0 z-30 flex items-center justify-between bg-blue-700 px-4 py-3 pt-4 pb-4 text-white shadow-md">
            <div class="flex items-center gap-3">
                <button @click="isSidebarOpen = true" class="rounded-lg p-1 hover:bg-white/20">
                    <Menu class="h-6 w-6" />
                </button>
                <span class="text-lg font-bold tracking-wide">SIPINJAM</span>
            </div>
            
            <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border border-white/50">
                <img v-if="user?.avatar" :src="user.avatar" :alt="user?.name" class="h-full w-full object-cover" />
                <span v-else class="text-xs font-bold">{{ getInitials(user?.name) }}</span>
            </div>
        </div>

        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="isSidebarOpen" 
                @click="closeSidebar" 
                class="fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm md:hidden"
            ></div>
        </transition>

        <aside 
            :class="[
                'fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-gradient-to-b from-blue-700 via-blue-600 to-indigo-700 text-white transition-transform duration-300 ease-in-out',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
                'md:translate-x-0' // Sidebar selalu tampil utuh di layar menengah ke atas (Desktop)
            ]"
        >
            <button 
                @click="closeSidebar" 
                class="absolute right-4 top-4 rounded-lg p-1 text-white/70 hover:bg-white/10 hover:text-white md:hidden"
            >
                <X class="h-6 w-6" />
            </button>

            <div class="flex flex-col items-center pt-10 md:pt-8 pb-6 px-5">
                <Link href="/profile" @click="closeSidebar" class="group flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full border-[3px] border-white/30 overflow-hidden bg-white/10 flex items-center justify-center mb-3 group-hover:border-blue-300 transition-colors">
                        <img v-if="user?.avatar" :src="user.avatar" :alt="user?.name" class="w-full h-full object-cover" />
                        <span v-else class="text-xl font-bold text-white/70">{{ getInitials(user?.name) }}</span>
                    </div>
                    <p class="text-sm font-bold text-white truncate max-w-[200px] group-hover:text-blue-200 transition-colors">{{ user?.name || 'User' }}</p>
                    <p class="text-[11px] text-blue-200/70 truncate max-w-[200px]">{{ user?.email || '' }}</p>
                </Link>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 space-y-1 pb-4 custom-scrollbar">
                <Link 
                    href="/dashboard"
                    @click="closeSidebar"
                    :class="[
                        'flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200',
                        currentUrl === '/dashboard'
                            ? 'bg-white/20 text-white shadow-sm'
                            : 'text-blue-100/80 hover:bg-white/10 hover:text-white',
                    ]"
                >
                    <Home class="h-[18px] w-[18px] shrink-0" />
                    <span class="truncate">Dashboard</span>
                </Link>

                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    @click="closeSidebar"
                    :class="[
                        'flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200',
                        isActive(item.href)
                            ? 'bg-white/20 text-white shadow-sm'
                            : 'text-blue-100/80 hover:bg-white/10 hover:text-white',
                    ]"
                >
                    <component :is="item.icon" class="h-[18px] w-[18px] shrink-0" />
                    <span class="truncate">{{ item.label }}</span>
                </Link>
            </nav>

            <div class="flex items-center justify-center px-6 py-4 mt-auto">
                <img :src="sidebarLogo" alt="SIPINJAM" class="h-24 md:h-28 w-auto object-contain opacity-80" />
            </div>

            <div class="px-4 pb-5">
                <button
                    @click="logout"
                    class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-blue-100/80 hover:bg-white/10 hover:text-white transition-all duration-200"
                >
                    <LogOut class="h-[18px] w-[18px] shrink-0" />
                    <span>Keluar</span>
                </button>
            </div>
        </aside>
    </div>
</template>

<style scoped>
/* Opsional: Membuat scrollbar navigasi terlihat lebih tipis/bersih jika menu bertambah banyak */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}
</style>