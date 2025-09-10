<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(
            [
                "name" => "Admin",
                "created_at" => now(),
                "updated_at" => null,
            ]
        );
        Role::create(
            [
                "name" => "User",
                "created_at" => now(),
                "updated_at" => null,
            ]
        );
    }
}
