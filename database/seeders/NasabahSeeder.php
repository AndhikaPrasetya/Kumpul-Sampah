<?php

namespace Database\Seeders;

use App\Models\NasabahDetail;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nasabahsData = [
    ['name' => ' Zakari', 'balance' => '120.466,73'],
    ['name' => ' Jojo', 'balance' => '244.515,71'],
    ['name' => ' Sadun', 'balance' => '8.253,12'],
    ['name' => ' Putri Jaka', 'balance' => '43.714,74'],
    ['name' => ' Siti Sahara', 'balance' => '219.515,15'],
    ['name' => ' Sri Kwatiningsih', 'balance' => '207.023,73'],
    ['name' => ' Rini', 'balance' => '382.918,80'],
    ['name' => ' Siti Lestari', 'balance' => '116.737'],
    ['name' => ' Repelita', 'balance' => '85487,40'],
    ['name' => ' Jumadi', 'balance' => '3.932,65'],
    ['name' => ' Siti Saadah', 'balance' => '8.750'],
    ['name' => ' Samnuh', 'balance' => '3.840'],
    ['name' => ' Ati', 'balance' => '15.025'],
    ['name' => ' Enung', 'balance' => '80.561,61'],
    ['name' => ' Eny Yuliasih', 'balance' => '28.810'],
    ['name' => 'Ros', 'balance' => '13.950,80'],
    ['name' => ' Arya', 'balance' => '2.272,13'],
    ['name' => ' Kelly', 'balance' => '65.947,40'],
    ['name' => '.Suwarsa', 'balance' => '85.406,83'],
    ['name' => ' Misrohayati', 'balance' => '204.698,91'],
    ['name' => ' Agustin', 'balance' => '112.754'],
    ['name' => ' Aliyahman', 'balance' => '287,25'],
    ['name' => ' Yaman Coprak', 'balance' => '0'],
    ['name' => ' Rosmine', 'balance' => '31.024,35'],
    ['name' => ' Effendi', 'balance' => '88.309,35'],
    ['name' => ' Dasman', 'balance' => '26.131,45'],
    ['name' => ' Atih', 'balance' => '71.800,05'],
    ['name' => ' May', 'balance' => '21.277,63'],
    ['name' => ' Masruhan', 'balance' => '44.928,45'],
    ['name' => ' Ikhsan', 'balance' => '359,10'],
    ['name' => ' Samsi', 'balance' => '882,15'],
    ['name' => ' Tini', 'balance' => '52.427,15'],
];

foreach ($nasabahsData as $data) {
    $name = $data['name'];
    $rawBalance = $data['balance'];

    // Membersihkan nama untuk digunakan di email (menghilangkan spasi, tanda baca, dan mengubah ke huruf kecil)
$email = strtolower(str_replace(' ', '', $name)) . '@gmail.com';

    // Membersihkan saldo dari tanda titik dan koma, lalu mengubahnya menjadi float
    $balance = (float) str_replace(',', '.', str_replace('.', '', $rawBalance));

    // Membuat user baru
    $nasabah = User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt('password'), // Password default
    ]);

    // Menetapkan peran 'nasabah'
    // Pastikan Anda memiliki package Spatie/Laravel-Permission terinstal dan dikonfigurasi
    // Atau sesuaikan dengan sistem peran Anda
    $nasabah->assignRole('nasabah');

    // Membuat detail nasabah
    $nasabahDetail = new NasabahDetail();
    $nasabahDetail->user_id = $nasabah->id;
    $nasabahDetail->bsu_id = 16; // Nilai default bsu_id
    $nasabahDetail->save();

    // Membuat saldo untuk nasabah
    $saldo = new Saldo();
    $saldo->user_id = $nasabah->id;
    $saldo->bsu_id = 16; // Nilai default bsu_id
    $saldo->balance = $balance;
    $saldo->points = 0; // Poin default
    $saldo->save();

    echo "Pengguna '{$name}' dengan email '{$email}' dan saldo '{$balance}' berhasil dibuat.\n";
}
    }
}
