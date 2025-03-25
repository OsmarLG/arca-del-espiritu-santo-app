<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MinisterioMisericordiaRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_menu_ministerios'],
            ['name' => 'view_menu_misericordia'],
            ['name' => 'view_any_producto_misericordia'],
            ['name' => 'create_producto_misericordia'],
            ['name' => 'update_producto_misericordia'],
            ['name' => 'delete_producto_misericordia'],
            ['name' => 'view_any_categoria_misericordia'],
            ['name' => 'create_categoria_misericordia'],
            ['name' => 'update_categoria_misericordia'],
            ['name' => 'delete_categoria_misericordia'],
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
