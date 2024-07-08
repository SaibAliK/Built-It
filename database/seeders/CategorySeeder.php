<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
            $city = Category::create([
                'parent_id' => 0,
                'name' => ['en'=>$faker->name, 'ar'=>$arFaker->name()],
            ]);

            for ($i1=0; $i1 < 3; $i1++) {
                $area = Category::create([
                    'parent_id' => $city->id,
                    'name' => ['en'=>$faker->streetName(), 'ar'=>$arFaker->streetName()],
                ]);
            }

        }
    }
}
