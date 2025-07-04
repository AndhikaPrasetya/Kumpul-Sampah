<?php

namespace Database\Seeders;

use App\Models\BsuDetail;
use App\Models\CategorySampah;
use App\Models\NasabahDetail;
use App\Models\Saldo;
use Exception;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create role',
            'read role',
            'update role',
            'delete role',
            'create permission',
            'read permission',
            'update permission',
            'delete permission',
            'create user',
            'read user',
            'update user',
            'delete user',
            'create kategori',
            'read kategori',
            'update kategori',
            'delete kategori',
            'create website setting',
            'read website setting',
            'update website setting',
            'delete website setting',
            'create sampah',
            'read sampah',
            'update sampah',
            'delete sampah',
            'create saldo',
            'read saldo',
            'update saldo',
            'delete saldo',
            'create transaction',
            'read transaction',
            'update transaction',
            'delete transaction',
            'create histori transaction',
            'read histori transaction',
            'update histori transaction',
            'delete histori transaction',
            'create withdraw',
            'read withdraw',
            'update withdraw',
            'delete withdraw',
            'create rewards',
            'read rewards',
            'update rewards',
            'delete rewards',
            'create penukaran points',
            'read penukaran points',
            'update penukaran points',
            'delete penukaran points',
            'create nasabah',
            'read nasabah',
            'update nasabah',
            'delete nasabah',
            'create article',
            'read article',
            'update article',
            'delete article',
            'create bsu',
            'read bsu',
            'update bsu',
            'delete bsu',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Update cache for permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create admin role and assign permissions
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => 'super admin']);
            $bsu = Role::create(['name' => 'bsu']);
            $nasabah = Role::create(['name' => 'nasabah']);

            $bsu->givePermissionTo([
                'create kategori',
                'read kategori',
                'update kategori',
                'delete kategori',
                'create sampah',
                'read sampah',
                'update sampah',
                'delete sampah',
                'create saldo',
                'read saldo',
                'update saldo',
                'delete saldo',
                'create transaction',
                'read transaction',
                'update transaction',
                'delete transaction',
                'create histori transaction',
                'read histori transaction',
                'update histori transaction',
                'delete histori transaction',
                'create withdraw',
                'read withdraw',
                'update withdraw',
                'delete withdraw',
                'create rewards',
                'read rewards',
                'update rewards',
                'delete rewards',
                'create penukaran points',
                'read penukaran points',
                'update penukaran points',
                'delete penukaran points',
                'create nasabah',
                'read nasabah',
                'update nasabah',
                'delete nasabah',
                'create article',
                'read article',
                'update article',
                'delete article',
            ]);

            $nasabah->givePermissionTo([
                'create transaction',
                'read transaction',
                'update transaction',
                'delete transaction',
                'create withdraw',
                'read withdraw',
                'update withdraw',
                'delete withdraw',
            ]);
            $role->givePermissionTo(Permission::all());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Seeding failed: ' . $e->getMessage());
        }

        $dataBsu = [
            'BSU Karya Perduli ',
            'BSU Madrasah Raudiatul Mutaalimin',
            'BSU Melati 1 ',
            'BSU Melati 2 ',
            'BSU Agathis Botanical ',
            'BSU Surya Mandiri',
            'BSU Asri',
            'BSU Raflesia',
            'BSU Jack ',
            'BSU RPTRA Mahkota',
            'BSU RPTRA Meruya Selatan',
            'BSU RPTRA Menuver',
            'BSU RPTRA Menara',
            'BSU RPTRA Manunggal',
            'BSU Sumber Rejeki ',
            'BSU Rosmerah ',
            'BSU Anggrek Bulan ',
            'BSU Camal Jaya ',
            'BSU Pelangi ',
            'BSU Lestari'
        ];

        // Create Super Admin user
        DB::beginTransaction();
        try {
            $admin = User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ],);

            $bsus = [];
            foreach ($dataBsu as $bsu) {
                $emailBsu =strtolower(str_replace(' ', '', $bsu) . '@gmail.com');
                $bsu = User::create([
                    'name' => $bsu,
                    'email' => $emailBsu,
                    'password' => bcrypt('password'),
                ]);

                $bsus[] = $bsu;
            }

            foreach ($bsus as $bsu) {
                $bsu->assignRole('bsu');
                $bsuDetail = new BsuDetail();
                $bsuDetail->user_id = $bsu->id;
                $bsuDetail->rt = '01';
                $bsuDetail->rw = '08';
                $bsuDetail->kelurahan = 'Meruya Selatan';
                $bsuDetail->alamat = 'jl.H.saaba';
                $bsuDetail->save();
            }

            $nasabah = User::create([
                'name' => 'nasabah',
                'email' => 'nasabah@gmail.com',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('super admin');
            $nasabah->assignRole('nasabah');

            $nasabahDetail = new NasabahDetail();
            $nasabahDetail->user_id = $nasabah->id;
            $nasabahDetail->bsu_id = $bsu[0]->id;
            $nasabahDetail->alamat = 'jl.H.saaba';
            $nasabahDetail->save();

            $saldo = new Saldo();
            $saldo->user_id = $nasabah->id;
            $saldo->bsu_id = $bsu[0]->id;
            $saldo->balance = 0;
            $saldo->points = 0;
            $saldo->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Seeding failed: ' . $e->getMessage());
        }

        //DATA SETTING WEBSITE
        DB::beginTransaction();
        try {
            $data = [
                'website_name' => 'Kumpul Sampah',
                'website_description' => 'Kumpul Sampah adalah sistem pengelolaan data.',
                'logo' => 'logos/3135715.png',
                'favicon' => 'favicons/3135715.png',
            ];

            // Simpan data ke database
            WebsiteSetting::create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        //DATA Kategori sampah
        DB::beginTransaction();
        try {
            $categories = [
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
                'Mainan'
            ];

            foreach ($categories as $category) {
                CategorySampah::create([
                    'bsu_id' => $bsu->id,
                    'nama' => $category,
                    'deskripsi' => 'sampah ' . $category,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
