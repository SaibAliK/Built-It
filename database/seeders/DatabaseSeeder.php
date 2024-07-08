<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        $this->call([
            CitySeeder::class,
            PageSeeder::class,
            SettingSeeder::class,
        ]);
        $path = env("PUBLIC_PATH") . 'uploads/admin/';
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $image = $faker->image($path, 555, 372, null, false);
        $imagePath = 'uploads/admin/' . $image;
        DB::table('admins')->insert([
            [
                'name' => "Super Admin",
                'email' => "admin@seven.com",
                'password' => bcrypt("123456"),
                'image' => $imagePath,
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => now()->unix(),
                'updated_at' => now()->unix(),
            ],
        ]);

        DB::table('users')->insert([
            [
                'city_id' => "1",
                'email' => "supplier@gmail.com",
                'password' => bcrypt("123456"),
                'image' => $imagePath,
                'id_card_images' => $imagePath,
                'phone' => '+34234243423',
                'is_active' => 1,
                'is_verified' => 1,
                'is_id_card_verified' => 1,
                'address' => 'Pakistan',
                'latitude' => '31.5204',
                'longitude' => '74.3587',
                'user_type' => 'supplier',
                'supplier_name' => '{"en":"Supplier Name","ar":"Name"}',
                'about' => '{"en":"About","ar":"About"}',
                'created_at' => now()->unix(),
                'updated_at' => now()->unix(),
            ],
        ]);

        DB::table('users')->insert([
            [
                'city_id' => "1",
                'email' => "user@gmail.com",
                'password' => bcrypt("123456"),
                'image' => $imagePath,
                'phone' => '+34234243423',
                'is_active' => 1,
                'is_verified' => 1,
                'address' => 'Pakistan',
                'latitude' => '31.5204',
                'longitude' => '74.3587',
                'user_type' => 'user',
                'user_name' => 'user name',
                'about' => '{"en":"About","ar":"About"}',
                'created_at' => now()->unix(),


                'updated_at' => now()->unix(),
            ],
        ]);

        DB::table('subscription_packages')->insert([
            [
                'duration' => '1',
                'duration_type' => 'days',
                'price' => '0.00',
                'name' => '{"en":"Free Package","ar":"Free"}',
                'subscription_type' => 'subscription',
                'description' => '{"en":"<p>free package<\/p>","ar":"<p>free package<\/p>"}',
                'is_free' => '1',
                'created_at' => now()->unix(),
                'updated_at' => now()->unix(),
            ],
        ]);

        DB::table('subscription_packages')->insert([
            [
                'duration' => '1',
                'duration_type' => 'months',
                'price' => '10.00',
                'name' => '{"en":"1 Month Package","ar":"Month"}',
                'subscription_type' => 'subscription',
                'description' => '{"en":"<p>1 month package<\/p>","ar":"<p>1 month package<\/p>"}',
                'is_free' => '0',
                'created_at' => now()->unix(),
                'updated_at' => now()->unix(),
            ],
        ]);

    }
}
