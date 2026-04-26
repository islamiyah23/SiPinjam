<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Rule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipinjam.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@sipinjam.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@sipinjam.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ── Rooms ──
        $room1 = Room::create([
            'name' => 'Ruang Rapat Utama',
            'capacity' => 20,
            'description' => 'Proyektor, AC, Whiteboard, Sound System',
            'building' => 'Gedung A',
            'floor' => 1,
            'image_url' => '/placeholder.svg',
        ]);

        $room2 = Room::create([
            'name' => 'Lab Komputer 1',
            'capacity' => 40,
            'description' => 'Komputer, AC, Proyektor, Internet',
            'building' => 'Gedung B',
            'floor' => 2,
            'image_url' => '/placeholder.svg',
        ]);

        $room3 = Room::create([
            'name' => 'Aula Serbaguna',
            'capacity' => 100,
            'description' => 'Sound System, Proyektor, AC, Panggung',
            'building' => 'Gedung C',
            'floor' => 1,
            'image_url' => '/placeholder.svg',
        ]);

        $room4 = Room::create([
            'name' => 'Ruang Seminar',
            'capacity' => 50,
            'description' => 'Proyektor, AC, Microphone, Whiteboard',
            'building' => 'Gedung A',
            'floor' => 3,
            'image_url' => '/placeholder.svg',
        ]);

        // ── Equipment ──
        $eq1 = Equipment::create([
            'name' => 'Proyektor Epson',
            'category' => 'Elektronik',
            'quantity' => 5,
            'available' => 3,
            'description' => 'Proyektor HD 3000 lumens',
            'image_url' => '/placeholder.svg',
        ]);

        $eq2 = Equipment::create([
            'name' => 'Laptop ASUS',
            'category' => 'Komputer',
            'quantity' => 10,
            'available' => 7,
            'description' => 'Laptop ASUS VivoBook 14',
            'image_url' => '/placeholder.svg',
        ]);

        $eq3 = Equipment::create([
            'name' => 'Speaker Portable',
            'category' => 'Audio',
            'quantity' => 3,
            'available' => 2,
            'description' => 'Speaker Bluetooth 20W',
            'image_url' => '/placeholder.svg',
        ]);

        $eq4 = Equipment::create([
            'name' => 'Kamera DSLR Canon',
            'category' => 'Fotografi',
            'quantity' => 2,
            'available' => 1,
            'description' => 'Canon EOS 200D dengan lensa kit',
            'image_url' => '/placeholder.svg',
        ]);

        // ── Bookings ──
        Booking::create([
            'user_id' => $user->id,
            'type' => 'room',
            'room_id' => $room1->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(2),
            'purpose' => 'Rapat organisasi mahasiswa',
            'status' => 'PENDING',
        ]);

        Booking::create([
            'user_id' => $user->id,
            'type' => 'equipment',
            'equipment_id' => $eq1->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(3),
            'purpose' => 'Presentasi tugas akhir',
            'status' => 'APPROVED',
            'approved_at' => now(),
        ]);

        Booking::create([
            'user_id' => $user2->id,
            'type' => 'room',
            'room_id' => $room2->id,
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(5),
            'purpose' => 'Praktikum pemrograman web',
            'status' => 'COMPLETED',
            'approved_at' => now()->subDays(7),
        ]);

        Booking::create([
            'user_id' => $user2->id,
            'type' => 'equipment',
            'equipment_id' => $eq2->id,
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
            'purpose' => 'Workshop desain grafis',
            'status' => 'REJECTED',
            'rejection_reason' => 'Laptop sedang dalam maintenance',
        ]);

        Booking::create([
            'user_id' => $user->id,
            'type' => 'room',
            'room_id' => $room3->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(10),
            'purpose' => 'Seminar nasional teknologi',
            'status' => 'COMPLETED',
            'approved_at' => now()->subDays(12),
        ]);

        // ── Rules ──
        $rules = [
            ['title' => 'Persyaratan Umum', 'content' => 'Peminjam harus terdaftar sebagai mahasiswa atau staf aktif institusi. Peminjaman hanya dapat dilakukan melalui sistem SIPINJAM.', 'order' => 1],
            ['title' => 'Prosedur Peminjaman', 'content' => 'Ajukan peminjaman minimal 2 hari sebelum tanggal penggunaan. Pastikan mengisi formulir dengan lengkap termasuk tujuan peminjaman.', 'order' => 2],
            ['title' => 'Durasi Peminjaman', 'content' => 'Peminjaman ruangan maksimal 1 hari per sesi. Peminjaman barang maksimal 7 hari dan dapat diperpanjang jika tersedia.', 'order' => 3],
            ['title' => 'Tanggung Jawab', 'content' => 'Peminjam bertanggung jawab penuh atas kondisi ruangan/barang selama masa peminjaman. Kerusakan atau kehilangan menjadi tanggung jawab peminjam.', 'order' => 4],
            ['title' => 'Pengembalian', 'content' => 'Ruangan harus dikembalikan dalam keadaan bersih dan rapi. Barang harus dikembalikan tepat waktu dan dalam kondisi baik.', 'order' => 5],
            ['title' => 'Sanksi', 'content' => 'Keterlambatan pengembalian dapat dikenakan sanksi penangguhan peminjaman. Pelanggaran berulang dapat mengakibatkan pencabutan hak peminjaman.', 'order' => 6],
        ];

        foreach ($rules as $rule) {
            Rule::create($rule);
        }
    }
}
