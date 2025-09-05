<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => 'P0001',
            'name' => 'Laptop',
            'description' => 'Laptop Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0002',
            'name' => 'Smartphone',
            'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0003',
            'name' => 'SmartTV',
            'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0004',
            'name' => 'Smartwatch',
            'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0005',
            'name' => 'Ipad',
            // 'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0006',
            'name' => 'Iwatch',
            // 'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0007',
            'name' => 'Macbook',
            // 'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0008',
            'name' => 'Lain lain',
            // 'description' => 'Smartphone Deskription'
        ]);
    }
}
