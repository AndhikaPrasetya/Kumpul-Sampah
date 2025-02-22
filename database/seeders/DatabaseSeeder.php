<?php

namespace Database\Seeders;

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
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('super admin');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Seeding failed: ' . $e->getMessage());
        }

        //DATA SETTING WEBSITE
        DB::beginTransaction();
        try{
        $data = [
            'website_name' => 'Kumpul Sampah',
            'website_description' => 'Kumpul Sampah adalah sistem pengelolaan data.',
            'logo' => 'logos/3135715.png',
            'favicon' => 'favicons/3135715.png',
        ];

        // Simpan data ke database
        WebsiteSetting::create($data);
        DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
      
    }
}
