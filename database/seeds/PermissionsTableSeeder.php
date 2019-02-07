<?php

use Illuminate\Database\Seeder;
use App\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        $idRole = App\Role::where('name', 'Administrator')->value('id');
        DB::table('permission_role')->where('role_id', $idRole)->delete();
        Schema::enableForeignKeyConstraints();

        $allpermissions = DB::table('permissions')->insert([

            /* CREATE NEW USER'S PERMISSION'S */
            [
                'name'			=> 'create_user',
                'display_name' 	=> 'Crear Usuario',
                'description' 	=> 'permite crear usuarios',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'edit_user',
                'display_name' 	=> 'Editar Usuario',
                'description' 	=> 'permite editar usuarios',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'see_user',
                'display_name' 	=> 'Ver Usuarios',
                'description' 	=> 'permite ver usuarios',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'delete_user',
                'display_name' 	=> 'Eliminar Usuarios',
                'description' 	=> 'permite eliminar usuarios',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'			=> 'see_actions',
                'display_name' 	=> 'Ver Acciones',
                'description' 	=> 'Ver acciones',
                'created_at'    => date('Y-m-d H:i:s'),
            ],

            /* CREATE NEW ROLE'S PERMISSION'S */
            [
                'name'			=> 'create_role',
                'display_name' 	=> 'Crear Rol',
                'description' 	=> 'Crear nuevos roles',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'edit_role',
                'display_name' 	=> 'Editar Rol',
                'description' 	=> 'Editar roles existentes',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'see_role',
                'display_name' 	=> 'Ver Roles',
                'description' 	=> 'Ver la lista de roles registrados.',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'delete_role',
                'display_name' 	=> 'Eliminar Rol',
                'description' 	=> 'Te permite eliminar un rol',
                'created_at'    => date('Y-m-d H:i:s'),
            ],[
                'name'			=> 'assign_permission',
                'display_name' 	=> 'Asignar permisos',
                'description' 	=> 'Permite asignar permisos a roles existentes.',
                'created_at'    => date('Y-m-d H:i:s'),
            ],


        ]);

           $adminRole = \App\Role::where('name', 'admin')->first();
           $adminRole->attachPermissions(\App\Permission::all());

    }
}
