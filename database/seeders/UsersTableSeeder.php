<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
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
        for($i = 0; $i<5; $i++)
            DB::table('users')->insert([
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt("123456"),
                'remember_token' => Str::random(10),
            ]);
    }
}
