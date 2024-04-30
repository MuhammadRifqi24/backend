<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Makanan',
                'status' => true,
                'uuid' => Str::uuid()->getHex()->toString()
            ],
            [
                'name' => 'Minuman',
                'status' => true,
                'uuid' => Str::uuid()->getHex()->toString()
            ]
        ];
        DB::table('categories')->insert($datas);
    }
}
