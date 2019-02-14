<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ForumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::Crate();
        for ($i=0; $i < ; $i++) {
        DB::table('forums')->insert([
          'title'       => $faker->title,
          'description' => $faker->text
        ]);
        }
    }
}
