<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all existing users
        DB::table('users')->truncate();

        // Re-enable foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            'name' => '2301',
            'email' => 'yousef@app.com',
            'password' => Hash::make('2301'),
            'section_id' => 1,
            'sub_section_id' => 1,
            'employee_name' => 'yousef',
        ]);

    }
}