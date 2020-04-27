<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('as_users')->insert([
            'email' => 'superadmin@appsowl.com',
            'username' => 'super-admin',
            'password' => '$2a$13$UPj8EVJWvP73FZ9Ih0xzUebANa.Jwzy83Q4Nij.mDbivHo2pDGRE.',
            'user_role' => '4',
            'banned' => 'N',
            'permission' => 'account,maintainer',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'email_varified' => '1'
        ]);

        DB::table('as_user_details')->insert([
            'user_id' => DB::getPdo()->lastInsertId(),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '01916908068',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
