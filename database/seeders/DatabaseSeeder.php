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

        // Create Super Admin user
        DB::beginTransaction();
        try {
            $admin = User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ],);
            $bsu = User::create([
                'name' => 'bsu karya mulya',
                'email' => 'bsu@gmail.com',
                'password' => bcrypt('password'),
            ]);
            $bsu2 = User::create([
                'name' => 'bsu cinta kasih',
                'email' => 'bsu2@gmail.com',
                'password' => bcrypt('password'),
            ]);

            

            $nasabah = User::create([
                'name' => 'nasabah',
                'email' => 'nasabah@gmail.com',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('super admin');
            $nasabah->assignRole('nasabah');
            $bsu->assignRole('bsu');
            $bsu2->assignRole('bsu');

            $bsuDetail = new BsuDetail();
            $bsuDetail->user_id = $bsu->id;
            $bsuDetail->rt = '01';
            $bsuDetail->rw = '08';
            $bsuDetail->kelurahan = 'Meruya Selatan';
            $bsuDetail->alamat = 'jl.H.saaba';
            $bsuDetail->save();

            $bsuDetail2 = new BsuDetail();
            $bsuDetail2->user_id = $bsu2->id;
            $bsuDetail2->rt = '01';
            $bsuDetail2->rw = '08';
            $bsuDetail2->kelurahan = 'Meruya Selatan';
            $bsuDetail2->alamat = 'jl.H.saaba';
            $bsuDetail2->save();

            $nasabahDetail = new NasabahDetail();
            $nasabahDetail->user_id = $nasabah->id;
            $nasabahDetail->bsu_id = $bsu->id;
            $nasabahDetail->alamat = 'jl.H.saaba';
            $nasabahDetail->save();

            $saldo = new Saldo();
            $saldo->user_id = $nasabah->id;
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
            CategorySampah::create([
                'bsu_id' => $bsu->id,
                'nama' => 'Plastik',
                'deskripsi' => 'sampah plastik',
            ]);

            // Simpan data ke database

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
