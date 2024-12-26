<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("category")->insert([
            'id_loaisp' => 'sanpham1',
            'tenloaisp' => 'Thịt, cá, hải sản',
            'anh_loaisp' => 'thit.jpg',
            'trangthai' =>''
        ],
        
    );
       
    }
}
