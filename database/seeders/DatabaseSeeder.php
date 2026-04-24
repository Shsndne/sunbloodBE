<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StokDarah;
use App\Models\Feedback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ──────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@sunblood.id'],
            [
                'name'     => 'Admin Sunblood',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'phone'    => '08001234567',
            ]
        );

        // ── USER DEMO ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'user@sunblood.id'],
            [
                'name'     => 'Pengguna Demo',
                'password' => Hash::make('user123'),
                'role'     => 'user',
                'phone'    => '08119876543',
            ]
        );

        // ── STOK DARAH ─────────────────────────────────────────
        $hospitals = [
            [
                'nama_rs' => 'RSUP Dr. Kariadi',
                'foto'    => null,
                'stok_a_plus'   => 25, 'stok_a_minus'  => 8,
                'stok_b_plus'   => 18, 'stok_b_minus'  => 5,
                'stok_ab_plus'  => 12, 'stok_ab_minus' => 3,
                'stok_o_plus'   => 30, 'stok_o_minus'  => 10,
            ],
            [
                'nama_rs' => 'RS Telogorejo',
                'foto'    => null,
                'stok_a_plus'   => 15, 'stok_a_minus'  => 4,
                'stok_b_plus'   => 20, 'stok_b_minus'  => 6,
                'stok_ab_plus'  => 8,  'stok_ab_minus' => 2,
                'stok_o_plus'   => 22, 'stok_o_minus'  => 7,
            ],
            [
                'nama_rs' => 'RSUD Tugurejo',
                'foto'    => null,
                'stok_a_plus'   => 10, 'stok_a_minus'  => 3,
                'stok_b_plus'   => 14, 'stok_b_minus'  => 4,
                'stok_ab_plus'  => 6,  'stok_ab_minus' => 1,
                'stok_o_plus'   => 18, 'stok_o_minus'  => 5,
            ],
            [
                'nama_rs' => 'RS Elisabeth',
                'foto'    => null,
                'stok_a_plus'   => 20, 'stok_a_minus'  => 6,
                'stok_b_plus'   => 16, 'stok_b_minus'  => 5,
                'stok_ab_plus'  => 9,  'stok_ab_minus' => 2,
                'stok_o_plus'   => 25, 'stok_o_minus'  => 8,
            ],
            [
                'nama_rs' => 'RS Hermina Pandanaran',
                'foto'    => null,
                'stok_a_plus'   => 12, 'stok_a_minus'  => 3,
                'stok_b_plus'   => 11, 'stok_b_minus'  => 2,
                'stok_ab_plus'  => 5,  'stok_ab_minus' => 0,
                'stok_o_plus'   => 15, 'stok_o_minus'  => 4,
            ],
        ];

        // Hanya seed jika belum ada data
        if (StokDarah::count() === 0) {
            foreach ($hospitals as $hospital) {
                StokDarah::create($hospital);
            }
        }

        // ── FEEDBACK DEMO ──────────────────────────────────────
        if (Feedback::count() === 0) {
            $feedbackData = [
                [
                    'nama'   => 'Budi Santoso',
                    'email'  => 'budi@email.com',
                    'pesan'  => 'Aplikasi sangat membantu! Saya bisa dengan mudah mengetahui stok darah di rumah sakit terdekat.',
                    'rating' => 5,
                    'status' => 'belum_dibalas',
                ],
                [
                    'nama'   => 'Siti Rahayu',
                    'email'  => 'siti@email.com',
                    'pesan'  => 'Fitur darurat sangat berguna saat keluarga saya butuh darah mendesak. Terima kasih Sunblood!',
                    'rating' => 5,
                    'status' => 'belum_dibalas',
                ],
                [
                    'nama'   => 'Ahmad Fauzi',
                    'email'  => null,
                    'pesan'  => 'Mungkin bisa ditambahkan notifikasi WhatsApp saat permintaan darurat sudah diproses.',
                    'rating' => 4,
                    'status' => 'belum_dibalas',
                ],
            ];

            foreach ($feedbackData as $fb) {
                Feedback::create($fb);
            }
        }
    }
}