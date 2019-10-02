<?php
namespace Bpocallaghan\Titan\Seeds;
use Illuminate\Database\Seeder;
use Bpocallaghan\Titan\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        Role::truncate();

        // basic website only role
        Role::create([
            'icon'    => 'desktop',
            'name'    => 'Website',
            'slug'    => '/',
            'keyword' => 'website',
        ]);

        // basic user (website or/and admin or any other accounts)
        Role::create([
            'icon'    => 'user',
            'name'    => 'User',
            'slug'    => '/',
            'keyword' => 'user',
        ]);

        // base admin role (to be able to log into /admin)
        Role::create([
            'icon'    => 'user-secret',
            'name'    => 'Base Admin',
            'slug'    => '/admin',
            'keyword' => 'base_admin',
        ]);

        // admin
        Role::create([
            'icon'    => 'user-secret',
            'name'    => 'Admin',
            'slug'    => '/admin',
            'keyword' => 'admin',
        ]);

        // admin_super
        Role::create([
            'icon'    => 'user-secret',
            'name'    => 'Admin Super',
            'slug'    => '/admin',
            'keyword' => 'admin_super',
        ]);

        // admin_notify
        Role::create([
            'icon'    => 'comments',
            'name'    => 'Admin Notifications',
            'slug'    => '/admin',
            'keyword' => 'admin_notify',
        ]);

        // developer
        Role::create([
            'icon'    => 'universal-access',
            'name'    => 'Developer',
            'slug'    => '/admin',
            'keyword' => 'developer',
        ]);

        // will only be able to view /admin and /admin/analytics
        Role::create([
            'icon'    => 'user-circle',
            'name'    => 'Analytics',
            'slug'    => '/admin',
            'keyword' => 'analytics',
        ]);
    }
}