<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Admin',
                'role' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
            ],
            [
                'name' => 'Author',
                'role' => 'Author',
                'email' => 'author@admin.com',
                'password' => bcrypt('admin'),
            ]
        ];
        User::insert($items);
    }
}
