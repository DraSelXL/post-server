<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCount = User::count();

        $faker = FakerFactory::create();

        for ($i = 0; $i < $userCount; $i++) {
            $post = new Post();
            $post->user_id = $faker->numberBetween(1, $userCount);
            $post->title = $faker->sentence();
            $post->description = $faker->paragraph();
            $post->save();
        }
    }
}
