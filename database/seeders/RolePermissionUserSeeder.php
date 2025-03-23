<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            ["name" => "view_any_user"],
            ["name" => "create_user"],
            ["name" => "update_user"],
            ["name" => "view_user"],
            ["name" => "delete_user"],
            ["name" => "restore_user"],
            ["name" => "force_delete_user"],
            ["name" => "view_any_role"],
            ["name" => "view_role"],
            ["name" => "create_role"],
            ["name" => "update_role"],
            ["name" => "delete_role"],
            ["name" => "restore_role"],
            ["name" => "force_delete_role"],
            ["name" => "create_permission"],
            ["name" => "view_any_permission"],
            ["name" => "view_permission"],
            ["name" => "update_permission"],
            ["name" => "force_delete_permission"],
            ["name" => "restore_permission"],
            ["name" => "delete_permission"],
            ["name" => "asignar_role"],
            ["name" => "asignar_permission"],
            ["name" => "quitar_role"],
            ["name" => "quitar_permission"],
            ["name" => "view_menu_security"],
            ["name" => "view_menu_profile"],
            ["name" => "view_menu_dashboard"],
            ["name" => "view_any_notifications"],
            ["name" => "view_notifications"],
            ["name" => "mark_as_read_notifications"],
            ["name" => "mark_as_unread_notifications"],
            ["name" => "delete_notifications"],
            ["name" => "view_menu_notifications"],
            ["name" => "view_menu_users"],
            ["name" => "view_menu_logs"],
            ["name" => "view_menu_settings"],
            ["name" => "view_menu_consolidacion"],
            ["name" => "view_menu_consolidando"],
            ["name" => "view_menu_creyentes"],
            ["name" => "view_any_creyente"],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Crear roles
        $masterRole = Role::create(['name' => 'master']);
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'users']);
        $settingsRole = Role::create(['name' => 'settings']);
        $consolidatorRole = Role::create(['name' => 'consolidador']);
        $creyenteRole = Role::create(['name' => 'creyente']);
        $hijoRole = Role::create(['name' => 'hijo']);
        $discipuloRole = Role::create(['name' => 'discipulo']);
        $liderRole = Role::create(['name' => 'lider']);
        $maestroRole = Role::create(['name' => 'maestro']);
        $evangelistaRole = Role::create(['name' => 'evangelista']);
        $profetaRole = Role::create(['name' => 'profeta']);
        $madreEspiritualRole = Role::create(['name' => 'madre_espiritual']);
        $padreEspiritualRole = Role::create(['name' => 'padre_espiritual']);

        // Asignar permisos a roles
        $masterRole->givePermissionTo(Permission::all());

        // Permisos para el rol "users" (crear y permisos básicos relacionados con usuarios)
        $userPermissions = Permission::where('name', 'LIKE', '%user%')
            ->whereNotIn('name', ['force_delete_user', 'restore_user', 'asignar_role', 'quitar_role', 'asignar_permission', 'quitar_permission'])
            ->get();

        // Agregar permisos de asignar y quitar roles/permisos al admin
        $additionalUsersPermissions = Permission::whereIn('name', ['view_menu_profile', 'view_menu_dashboard', 'view_any_notifications', 'view_notifications', 'mark_as_read_notifications', 'mark_as_unread_notifications', 'delete_notifications', 'view_menu_notifications'])->get();

        $userPermissions = $userPermissions->merge($additionalUsersPermissions);

        $userRole->givePermissionTo($userPermissions);

        // Permisos para el rol "admin" (todo lo relacionado con usuarios, pero sin forzar ni restaurar)
        $adminPermissions = Permission::where('name', 'LIKE', '%user%')
            ->whereNotIn('name', ['force_delete_user', 'restore_user'])
            ->get();

        // Agregar permisos de asignar y quitar roles/permisos al admin
        $additionalAdminPermissions = Permission::whereIn('name', ['asignar_permission', 'quitar_permission', 'asignar_role', 'quitar_role', 'view_menu_profile', 'view_menu_dashboard', 'view_any_notifications', 'view_notifications', 'mark_as_read_notifications', 'mark_as_unread_notifications', 'delete_notifications', 'view_menu_notifications'])->get();

        $adminPermissions = $adminPermissions->merge($additionalAdminPermissions);

        $adminRole->givePermissionTo($adminPermissions);

        // Crear usuarios
        $osmar = User::create([
            'username' => 'osmarlg',
            'name' => 'Osmar Liera',
            'email' => 'osmarlg@app.liartechnologies.com',
            'email_verified_at' => now(),
            'avatar' => null,
            'viene_otra_iglesia' => false,
            'bautizado' => false,
            'password' => Hash::make('Osmarsito0603'),
        ]);
        $osmar->assignRole($masterRole);

        $midia = User::create([
            'username' => 'midia',
            'name' => 'Midia',
            'email' => 'midia@app.liartechnologies.com',
            'email_verified_at' => now(),
            'avatar' => null,
            'viene_otra_iglesia' => true,
            'bautizado' => true,
            'password' => Hash::make('Midia2025'),
        ]);
        $midia->assignRole($adminRole);

        $papaDaniel = User::create([
            'username' => 'papadaniel',
            'name' => 'Papa Daniel',
            'email' => 'papadaniel@app.liartechnologies.com',
            'email_verified_at' => now(),
            'avatar' => null,
            'viene_otra_iglesia' => true,
            'bautizado' => true,
            'password' => Hash::make('PapaDaniel2025'),
        ]);
        $papaDaniel->assignRole($adminRole);
        $papaDaniel->assignRole($padreEspiritualRole);
        
        $mamaDey = User::create([
            'username' => 'mamadey',
            'name' => 'Mama Dey',
            'email' => 'mamadey@app.liartechnologies.com',
            'email_verified_at' => now(),
            'avatar' => null,
            'viene_otra_iglesia' => true,
            'bautizado' => true,
            'password' => Hash::make('MamaDey2025'),
        ]);
        $mamaDey->assignRole($adminRole);
        $mamaDey->assignRole($madreEspiritualRole);

        $karina = User::create([
            'username' => 'karina',
            'name' => 'Karina',
            'email' => 'karina@app.liartechnologies.com',
            'email_verified_at' => now(),
            'avatar' => null,
            'viene_otra_iglesia' => true,
            'bautizado' => true,
            'password' => Hash::make('Karina2025'),
        ]);
        $karina->assignRole($adminRole);
        
        $creyente = User::create([
            'username' => 'creyente',
            'name' => 'Creyente',
            'email' => 'creyente@app.liartechnologies.com',
            'email_verified_at' => now(),
            'avatar' => null,
            'password' => Hash::make('password'),
            'status' => 0,
            'viene_otra_iglesia' => false,
            'bautizado' => false,
            'fecha_conversion' => now(),
        ]);
        $creyente->assignRole($creyenteRole);
        $creyentePermissions = $additionalUsersPermissions;
        $creyente->givePermissionTo($creyentePermissions);

    }
}
