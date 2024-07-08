<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            [
                'slug' => "about-us",
                'name' => json_encode(["en"=>"About Us","ar"=>"معلومات عنا"]),
                'content' => json_encode(["en"=>"About Us Content","ar"=>"من نحن المحتوى"]),
                'page_type' => "page",
                'image' => "images/default-image.jpg",
                'icon' => "images/default-image.jpg",
                'sort_order' => "0",
            ],
            [
                'slug' => "privacy-policy",
                'name' => json_encode(["en"=>"Privacy Policy","ar"=>"سياسة خاصة"]),
                'content' => json_encode(["en"=>"Privacy policy content","ar"=>"محتوى سياسة الخصوصية"]),
                'page_type' => "page",
                'image' => "images/default-image.jpg",
                'icon' => "images/default-image.jpg",
                'sort_order' => "0",
            ],
            [
                'slug' => "terms-and-conditions",
                'name' => json_encode(["en"=>"Terms and Conditions","ar"=>"الأحكام والشروط"]),
                'content' => json_encode(["en"=>"Terms and conditions content","ar"=>"محتوى الشروط والأحكام"]),
                'page_type' => "page",
                'image' => "images/default-image.jpg",
                'icon' => "images/default-image.jpg",
                'sort_order' => "0",
            ],
            [
                'slug' => "mission-and-vision",
                'name' => json_encode(["en"=>"mission and vision content","ar"=>"الأحكام والشروط"]),
                'content' => json_encode(["en"=>"mission and vision content","ar"=>"محتوى الشروط والأحكام"]),
                'page_type' => "page",
                'image' => "images/default-image.jpg",
                'icon' => "images/default-image.jpg",
                'sort_order' => "0",
            ],
        ]);
    }
}
