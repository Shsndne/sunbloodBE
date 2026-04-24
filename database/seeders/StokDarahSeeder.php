<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\StokDarah;

class StokDarahSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key check agar truncate tidak error
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StokDarah::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rumahSakit = [
            [
                'nama_rs'       => 'RSUP Dr. Kariadi',
                'stok_a_plus'   => rand(5, 60),
                'stok_a_minus'  => rand(0, 20),
                'stok_b_plus'   => rand(5, 60),
                'stok_b_minus'  => rand(0, 20),
                'stok_ab_plus'  => rand(5, 30),
                'stok_ab_minus' => rand(0, 10),
                'stok_o_plus'   => rand(10, 60),
                'stok_o_minus'  => rand(0, 20),
            ],
            [
                'nama_rs'       => 'RS Telogorejo',
                'stok_a_plus'   => rand(5, 60),
                'stok_a_minus'  => rand(0, 20),
                'stok_b_plus'   => rand(5, 60),
                'stok_b_minus'  => rand(0, 20),
                'stok_ab_plus'  => rand(5, 30),
                'stok_ab_minus' => rand(0, 10),
                'stok_o_plus'   => rand(10, 60),
                'stok_o_minus'  => rand(0, 20),
            ],
            [
                'nama_rs'       => 'PMI Kota Semarang',
                'stok_a_plus'   => rand(5, 60),
                'stok_a_minus'  => rand(0, 20),
                'stok_b_plus'   => rand(5, 60),
                'stok_b_minus'  => rand(0, 20),
                'stok_ab_plus'  => rand(5, 30),
                'stok_ab_minus' => rand(0, 10),
                'stok_o_plus'   => rand(10, 60),
                'stok_o_minus'  => rand(0, 20),
            ],
            [
                'nama_rs'       => 'RS Elizabeth',
                'stok_a_plus'   => rand(5, 60),
                'stok_a_minus'  => rand(0, 20),
                'stok_b_plus'   => rand(5, 60),
                'stok_b_minus'  => rand(0, 20),
                'stok_ab_plus'  => rand(5, 30),
                'stok_ab_minus' => rand(0, 10),
                'stok_o_plus'   => rand(10, 60),
                'stok_o_minus'  => rand(0, 20),
            ],
            [
                'nama_rs'       => 'RSUD K.R.M.T. Wongsonegoro',
                'stok_a_plus'   => rand(5, 60),
                'stok_a_minus'  => rand(0, 20),
                'stok_b_plus'   => rand(5, 60),
                'stok_b_minus'  => rand(0, 20),
                'stok_ab_plus'  => rand(5, 30),
                'stok_ab_minus' => rand(0, 10),
                'stok_o_plus'   => rand(10, 60),
                'stok_o_minus'  => rand(0, 20),
            ],
        ];

        foreach ($rumahSakit as $rs) {
            StokDarah::create($rs);
        }

        $this->command->info('✅ StokDarahSeeder selesai: ' . count($rumahSakit) . ' rumah sakit dibuat.');
    }
}