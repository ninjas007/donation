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
        DB::table('categories')->insert([
            'name' => 'Bencana Alam',
            'slug' => 'bencana-alam',
            'image' => 'https://media.suara.com/pictures/970x544/2019/12/10/39854-banjir.jpg', // data sementara
        ]);
    }
}
