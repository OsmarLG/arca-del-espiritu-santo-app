<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsFamiliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_menu_familias'],
            ['name' => 'view_any_familia'],
            ['name' => 'create_familia'],
            ['name' => 'update_familia'],
            ['name' => 'delete_familia'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // Asignar todos al rol master
        $master = Role::where('name', 'master')->first();
        if ($master) {
            $master->givePermissionTo(array_column($permissions, 'name'));
        }
    }
}
