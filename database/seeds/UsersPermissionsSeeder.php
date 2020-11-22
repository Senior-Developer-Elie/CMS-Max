<?php

use Illuminate\Database\Seeder;

use App\User;

class UsersPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Truncate Users, Roles, Permissions Table
        DB::statement("SET foreign_key_checks=0");
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement("SET foreign_key_checks=1");

        //Add Roles
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'super admin',
                'guard_name' => 'web',
            ]
        ]);

        //Add Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'manage blogs',
                'guard_name' => 'web',
            ],
            [
                'name' => 'content manager',
                'guard_name' => 'web',
            ],
            [
                'name' => 'blog images',
                'guard_name' => 'web',
            ],
            [
                'name' => 'writer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete ability',
                'guard_name' => 'web',
            ],
            [
                'name' => 'google ads',
                'guard_name' => 'web',
            ]
        ]);

        //Create Super Admin User
        $user = User::create([
            'name' => 'Sam Pizzo',
            'password' => bcrypt('password'),
            'email' => 'info@cmsmax.com',
        ]);

        $user->assignRole('super admin');
    }
}
