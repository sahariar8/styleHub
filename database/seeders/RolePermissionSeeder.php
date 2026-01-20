<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Admin
            'manage_products',
            'manage_orders',
            'manage_users',
            'manage_pages',
            'publish_content',
            'view_reports',

            // Customer
            'view_products',
            'add_to_cart',
            'place_order',
            'view_own_orders',
            'manage_profile',
            'write_review',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $seller = Role::firstOrCreate(['name' => 'seller']);
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $content = Role::firstOrCreate(['name' => 'content_manager']);

        $admin->givePermissionTo(Permission::all());
        $seller->givePermissionTo(['manage_products', 'manage_orders']);
        $content->givePermissionTo(['manage_pages', 'publish_content']);
        $customer->givePermissionTo([
            'view_products',
            'add_to_cart',
            'place_order',
            'view_own_orders',
            'manage_profile',
            'write_review',
        ]);


    }
}