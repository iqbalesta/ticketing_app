<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            [
                'nama' => 'Stadion Utama Gelora Bung Karno',
                'alamat' => 'Jl. Gerbang Pemuda No. 1',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'deskripsi' => 'Stadion utama dengan kapasitas terbesar di Indonesia',
                'kapasitas' => 100000,
            ],
            [
                'nama' => 'Gedung Pertunjukan Convention Center',
                'alamat' => 'Jl. Benda Raya No. 5',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'deskripsi' => 'Gedung pertunjukan modern dengan fasilitas lengkap',
                'kapasitas' => 5000,
            ],
            [
                'nama' => 'Taman Hiburan Ancol',
                'alamat' => 'Jl. Lodan Timur No. 7',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'deskripsi' => 'Area outdoor yang cocok untuk festival dan konser',
                'kapasitas' => 30000,
            ],
            [
                'nama' => 'Indoor Stadium Senayan',
                'alamat' => 'Jl. Pintu Satu Senayan',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'deskripsi' => 'Stadium indoor berstandar internasional',
                'kapasitas' => 8000,
            ],
            [
                'nama' => 'Theater Kota',
                'alamat' => 'Jl. Hayam Wuruk No. 10',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'deskripsi' => 'Theater untuk pertunjukan seni dan teater',
                'kapasitas' => 1000,
            ],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
