<?php
 
 namespace Database\Seeders;
 
 use App\Models\Link;
 use Illuminate\Database\Seeder;
 
 class LinksTableSeeder extends Seeder
 {
     /**
      * Run the database seeds.
      *
      * @return void
      */
     public function run(): void
     {
         // 因为我们在 LinkFactory 中只定义了 6 个链接, 所以我们只需要生成小于等于 6 个链接, 不然会报错
         Link::factory()->count(6)->create();
     }
 }