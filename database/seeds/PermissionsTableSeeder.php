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
            'display_name' => 'Usuarios',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-users',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-users',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-users',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-users',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'prospectings',
            'display_name' => 'Prospectos',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-prospectings',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-prospectings',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-prospectings',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-prospectings',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'suppliers',
            'display_name' => 'Proveedores',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-suppliers',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-suppliers',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-suppliers',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-suppliers',
                'display_name' => 'Eliminar',
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
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-customer-orders',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-customer-orders',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-customer-orders',
                'display_name' => 'Eliminar',
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
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-quotes',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-quotes',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-quotes',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'ProductType',
            'display_name' => 'Tipos de productos',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-products-type',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-products-type',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-products-type',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-products-type',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'TypeOfPayments',
            'display_name' => 'Formas de pagos',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-type-payments',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-type-payments',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-type-payments',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-type-payments',
                'display_name' => 'Eliminar',
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

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'Product',
            'display_name' => 'Productos',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-products',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-products',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-products',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-products',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'catalogs',
            'display_name' => 'Catalogos',
            'icon' => 'icon-folder'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-catalogs',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-catalogs',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-catalogs',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-catalogs',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);


        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'destinations',
            'display_name' => 'Destinos',
            'icon' => 'icon-folder'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-destinations',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-destinations',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-destinations',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-destinations',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'customers',
            'display_name' => 'Clientes',
            'icon' => 'icon-people'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-customers',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-customers',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-customers',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-customers',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'sales',
            'display_name' => 'Ventas',
            'icon' => 'icon-basket-loaded'
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
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-sales',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'take-payment-sales',
                'display_name' => 'Recibir pago',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'print-coupon',
                'display_name' => 'Imprimir cupón',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'payments',
            'display_name' => 'Pagos',
            'icon' => 'icon-wallet'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-payments',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-payments',
                'display_name' => 'Crear',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-payments',
                'display_name' => 'Actualizar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-payments',
                'display_name' => 'Eliminar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ]
        ]);

        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'reports',
            'display_name' => 'Reportes',
            'icon' => 'icon-graph'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-reports',
                'display_name' => 'Leer',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'export-pending-payments',
                'display_name' => 'Exportar pagos pendienes por cobrar',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'export-quotes',
                'display_name' => 'Exportar reporte de cotizaciones',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'export-sales',
                'display_name' => 'Exportar reporte de ventas',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
        ]);


        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'dashboard',
            'display_name' => 'Dashboard',
            'icon' => 'icon-graph'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'see-calendar',
                'display_name' => 'Visualizar Calendario',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'see-count-tracing',
                'display_name' => 'Visualizar Contador (Seguimiento)',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'see-count-sales',
                'display_name' => 'Visualizar Contador(Ventas)',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'see-count-quotes',
                'display_name' => 'Visualizar Contador(Cotizaciones)',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ], 
            [
                'name' => 'see-tracing',
                'display_name' => 'Visualizar Tabla de Seguimiento',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
            [
                'name' => 'see-top-products',
                'display_name' => 'Visualizar Productos (Top 5)',
                'guard_name' => 'web',
                'module_id' => $moduleId
            ],
        ]);

        // Assign permissions to admin role
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

    }
}
