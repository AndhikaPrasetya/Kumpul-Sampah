<?php

namespace Database\Seeders;

use App\Models\CategorySampah;
use App\Models\Sampah;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySampahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           //DATA Kategori sampah
        DB::beginTransaction();
       try {
    DB::beginTransaction();

    // Data kategori yang Anda berikan
    $categoriesData = [
        'Plastik',
        'Bodong A',
        'Tutup Botol',
        'Tutup Galon',
        'Ember Warna',
        'Ember Hitam',
        'Paralon',
        'Naso',
        'Kresek',
        'Galon Aqua',
        'Akrilik',
        'Gelas Kotor',
        'Inject',
        'Mainan',
    ];


    // Bagian untuk membuat kategori
    foreach ($categoriesData as $categoryName) {
        $category = CategorySampah::create([
            'bsu_id' => 16,
            'nama' => $categoryName,
            'deskripsi' => 'sampah ' . $categoryName,
        ]);
    }

    DB::commit();
    echo "Data kategori dan sampah berhasil dimasukkan!\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
        
    }
}
