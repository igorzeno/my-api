<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

class NewsTableSeeder extends Seeder
{
    protected $faker;
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
    public function run()
    {
        for($i = 0; $i<50; $i++)
            DB::table('news')->insert([
                'title' => $this->faker->catchPhrase,
                'details' => $this->faker->text,
                'created_by' => rand(1, 5)
            ]);
    }
}
