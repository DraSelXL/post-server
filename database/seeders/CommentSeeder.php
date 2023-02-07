<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCount = User::count();
        $postCount = Post::count();

        $faker = FakerFactory::create();

        for ($i = 0; $i < $userCount * 2; $i++) {
            $post = new Comment();
            $post->user_id = $faker->numberBetween(1, $userCount);
            $post->post_id = $faker->numberBetween(1, $postCount);
            $post->content = $faker->sentence();
            $post->save();
        }
    }
}
