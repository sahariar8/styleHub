<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $admin = User::firstOrCreate(
            ['email' => 'nishad@gmail.com'],
            [
                'name' => 'Nishad',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

    }
}
