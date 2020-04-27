<?php

use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('as_user_roles')->insert([
            'role' => 'user',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('as_user_roles')->insert([
            'role' => 'agent',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('as_user_roles')->insert([
            'role' => 'admin',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('as_user_roles')->insert([
            'role' => 'superadmin',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
