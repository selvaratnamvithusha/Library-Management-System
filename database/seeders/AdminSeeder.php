<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'svithusha1998@gmail.com',
            'phone' => '0773949659',
            'address' => 'Jaffna',
            'password' => bcrypt('admin123'),
            'usertype' => 1,
        ]); 
    }
}
