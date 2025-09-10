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
        //
        $name = ["Mai Huong", "Mon Mon", "Hai Duoi", "Anh Long", "Thanh Hóa", "Nam Định", "No Love No Life", "Nơi Anh Không Thuộc Về", "Đời Tàn Tình Tan", "Con Tim Mong Manh", "Con Đường Mưa", "Mai Trâm"];
        $countName = count($name) - 1;
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $name[rand(1, $countName)],
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => '2',
            ]);
        }
    }
}
