<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $thuCong = Category::firstOrCreate(
            ['slug' => 'thu-cong'],
            ['name' => 'Thủ công', 'position' => 1]
        );

        // Cấp 2
        $trangSuc = Category::firstOrCreate(
            ['slug' => 'trang-suc-handmade'],
            ['name' => 'Trang sức handmade', 'position' => 1, 'parent_id' => $thuCong->id]
        );
        $trangTri = Category::firstOrCreate(
            ['slug' => 'do-trang-tri'],
            ['name' => 'Đồ trang trí', 'position' => 2, 'parent_id' => $thuCong->id]
        );

        // Cấp 3
        $vongTay = Category::firstOrCreate(
            ['slug' => 'vong-tay'],
            ['name' => 'Vòng tay', 'position' => 1, 'parent_id' => $trangSuc->id]
        );
        $vongCo = Category::firstOrCreate(
            ['slug' => 'vong-co'],
            ['name' => 'Vòng cổ', 'position' => 2, 'parent_id' => $trangSuc->id]
        );
        $den = Category::firstOrCreate(
            ['slug' => 'den-handmade'],
            ['name' => 'Đèn handmade', 'position' => 1, 'parent_id' => $trangTri->id]
        );

        // Cấp 4
        Category::firstOrCreate(
            ['slug' => 'den-giay'],
            ['name' => 'Đèn giấy', 'position' => 1, 'parent_id' => $den->id]
        );
    }
}
