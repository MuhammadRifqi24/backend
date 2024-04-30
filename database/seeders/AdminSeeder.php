<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uuid = Str::uuid()->getHex()->toString();
        $id = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'uuid' => $uuid
        ]);

        DB::table('user_levels')->insert([
            'user_id' => $id,
            'level' => 'superadmin',
            'role' => 'admin'
        ]);
    }
}
