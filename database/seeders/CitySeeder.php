<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =  \Faker\Factory::create();
        $arFaker = \Faker\Factory::create('ar_SA');

        for ($i=0; $i < 3; $i++) {
            $city = City::create([
                'parent_id' => 0,
                'name' => ['en'=>$faker->city(), 'ar'=>$arFaker->city()],
            ]);

            for ($i1=0; $i1 < 3; $i1++) {
                $area = City::create([
                    'parent_id' => $city->id,
                    'name' => ['en'=>$faker->streetName(), 'ar'=>$arFaker->streetName()],
                ]);
            }

        }
    }
}
