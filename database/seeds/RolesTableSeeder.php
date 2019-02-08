<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create default roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin'
        ]);
        $user = Role::create([
            'name' => 'user',
            'display_name' => 'User'
        ]);


    }
}
