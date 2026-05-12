<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sparepart;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spareparts = [
            ['nama' => 'Oli Mesin 10W-40',        'harga' => 85000,   'deskripsi' => 'Oli mesin mineral untuk kendaraan bermotor roda dua'],
            ['nama' => 'Filter Udara Universal',  'harga' => 45000,   'deskripsi' => 'Filter udara karbu standar, cocok berbagai merek motor'],
            ['nama' => 'Busi NGK CR7HSA',         'harga' => 28000,   'deskripsi' => 'Busi standar NGK untuk motor bebek dan matic'],
            ['nama' => 'Kampas Rem Depan',        'harga' => 55000,   'deskripsi' => 'Kampas rem cakram depan, material semi-metallic'],
            ['nama' => 'Rantai Motor 428H',       'harga' => 120000,  'deskripsi' => 'Rantai motor ukuran 428H, 112 mata, standar pabrikan'],
            ['nama' => 'V-Belt Matic',            'harga' => 95000,   'deskripsi' => 'V-Belt untuk motor matic, tahan lama dan anti selip'],
            ['nama' => 'Aki Kering 5Ah',          'harga' => 185000,  'deskripsi' => 'Aki kering MF maintenance-free kapasitas 5Ah 12V'],
            ['nama' => 'Kampas Kopling Set',      'harga' => 75000,   'deskripsi' => 'Set kampas kopling 4 pcs untuk motor bebek manual'],
            ['nama' => 'Shock Absorber Belakang', 'harga' => 320000,  'deskripsi' => 'Shockbreaker belakang standar, gas pressure'],
            ['nama' => 'Lampu Depan H4 35/35W',  'harga' => 38000,   'deskripsi' => 'Lampu halogen H4 dua filamen standar motor bebek'],
            ['nama' => 'Minyak Rem DOT3',         'harga' => 22000,   'deskripsi' => 'Cairan rem DOT3 untuk sistem rem hidrolik cakram'],
            ['nama' => 'Gear Set 428H 14-42T',   'harga' => 145000,  'deskripsi' => 'Gear depan & belakang paket, bahan steel hardened'],
        ];

        foreach ($spareparts as $sp) {
            Sparepart::create($sp);
        }
    }
}
