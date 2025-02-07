<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Editor']);

        Permission::create(['name' => 'Visualizar usuarios'])->assignRole($role1);
        Permission::create(['name' => 'Crear usuario'])->assignRole($role1);
        Permission::create(['name' => 'Eliminar usuario'])->assignRole($role1);

        Permission::create(['name' => 'Crear actividad'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Editar actividad'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar actividad'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Subir archivo actividad'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Actualizar archivo actividad'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar archivo actividad'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Crear convocatoria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Editar convocatoria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar convocatoria'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Subir archivo convocatoria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Actualizar archivo convocatoria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar archivo convocatoria'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Crear martiana'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Editar martiana'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar martiana'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Subir archivo martiana'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Actualizar archivo martiana'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar archivo martiana'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Crear galeria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Editar galeria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar galeria'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Subir foto galeria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Actualizar foto galeria'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar foto galeria'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'Crear directorio'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Editar directorio'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'Eliminar directorio'])->syncRoles([$role1,$role2]);

        // Crear usuario de prueba con el rol "Admin"
        $user = User::firstOrCreate(
            ['email' => 'hguerreromacias15@gmail.com'], // Evita duplicados
            [
                'name' => 'Hector Jesheck Guerrero Macias',
                'password' => bcrypt('12345678')
            ]
        );

        // Asignar el rol "Admin" al usuario
        if (!$user->hasRole('Admin')) {
            $user->assignRole($role1);
            $this->command->info('✅ Usuario de prueba creado y asignado al rol "Admin".');
        } else {
            $this->command->info('🔹 El usuario ya tenía el rol "Admin".');
        }

        $us = User::firstOrCreate(
            ['email' => '2121100401@soy.utj.edu.mx'], // Evita duplicados
            [
                'name' => 'Jesus Alfonzo Perez Martinez',
                'password' => bcrypt('12345678')
            ]
        );

        // Asignar el rol "Admin" al usuario
        if (!$us->hasRole('Editor')) {
            $us->assignRole($role2);
            $this->command->info('✅ Usuario de prueba creado y asignado al rol "Editor".');
        } else {
            $this->command->info('🔹 El usuario ya tenía el rol "Editor".');
        }
    }
}
