<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        DB::table('modules')->truncate();

        Schema::enableForeignKeyConstraints();

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'roles',
            'display_name' => 'Roles',
            'icon' => 'icon-key'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-roles',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-roles',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-roles',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-roles',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'users',
            'display_name' => 'Users',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-users',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-users',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-users',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-users',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'prospectings',
            'display_name' => 'Prospectings',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-prospectings',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-prospectings',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-prospectings',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-prospectings',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'suppliers',
            'display_name' => 'Suppliers',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-suppliers',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-suppliers',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-suppliers',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-suppliers',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'request_quotes',
            'display_name' => 'Solicitudes de cotizaciones',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-customer-orders',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-customer-orders',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-customer-orders',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-customer-orders',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);


        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'quotes',
            'display_name' => 'Cotizaciones',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-quotes',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-quotes',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-quotes',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-quotes',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'ProductType',
            'display_name' => 'products-type',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-products-type',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-products-type',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-products-type',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-products-type',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'suppliers',
            'display_name' => 'Suppliers',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-suppliers',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-suppliers',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-suppliers',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-suppliers',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);


        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'sales',
            'display_name' => 'Ventas',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-sales',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-sales',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-sales',
                'display_name' => 'ACtualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-sales',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'Product',
            'display_name' => 'products',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-products',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-products',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-products',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-products',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'catalogs',
            'display_name' => 'Catalogs',
            'icon' => 'icon-folder'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-catalogs',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-catalogs',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-catalogs',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-catalogs',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'customers',
            'display_name' => 'Customers',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-customers',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-customers',
                'display_name' => 'Create',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-customers',
                'display_name' => 'Update',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-customers',
                'display_name' => 'Delete',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'sales',
            'display_name' => 'Sales',
            'icon' => 'icon-basket-loaded'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-sales',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'payments',
            'display_name' => 'Payments',
            'icon' => 'icon-wallet'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-payments',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'reports',
            'display_name' => 'Reports',
            'icon' => 'icon-graph'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-reports',
                'display_name' => 'Read',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
        ]);

        // Assign permissions to admin role
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

    }
}
