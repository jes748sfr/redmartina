<?php

namespace Database\Seeders;

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

        Permission::create(['name' => 'Visualizar usuarios']);
        Permission::create(['name' => 'Crear usuario']);
        Permission::create(['name' => 'Eliminar usuario']);

        Permission::create(['name' => 'Crear actividad']);
        Permission::create(['name' => 'Editar actividad']);
        Permission::create(['name' => 'Eliminar actividad']);

        Permission::create(['name' => 'Subir archivo actividad']);
        Permission::create(['name' => 'Actualizar archivo actividad']);
        Permission::create(['name' => 'Eliminar archivo actividad']);

        Permission::create(['name' => 'Crear convocatoria']);
        Permission::create(['name' => 'Editar convocatoria']);
        Permission::create(['name' => 'Eliminar convocatoria']);

        Permission::create(['name' => 'Subir archivo convocatoria']);
        Permission::create(['name' => 'Actualizar archivo convocatoria']);
        Permission::create(['name' => 'Eliminar archivo convocatoria']);

        Permission::create(['name' => 'Crear martiana']);
        Permission::create(['name' => 'Editar martiana']);
        Permission::create(['name' => 'Eliminar martiana']);

        Permission::create(['name' => 'Subir archivo martiana']);
        Permission::create(['name' => 'Actualizar archivo martiana']);
        Permission::create(['name' => 'Eliminar archivo martiana']);

        Permission::create(['name' => 'Crear galeria']);
        Permission::create(['name' => 'Editar galeria']);
        Permission::create(['name' => 'Eliminar galeria']);

        Permission::create(['name' => 'Subir foto galeria']);
        Permission::create(['name' => 'Actualizar foto galeria']);
        Permission::create(['name' => 'Eliminar foto galeria']);

        Permission::create(['name' => 'Crear directorio']);
        Permission::create(['name' => 'Editar directorio']);
        Permission::create(['name' => 'Eliminar directorio']);
    }
}
