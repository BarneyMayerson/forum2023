<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;

use function Laravel\Prompts\progress;

class LikeLoadTestSeeder extends Seeder
{
    public function run(): void
    {
        $post = Post::find(1);

        $progress = progress(label: "Creating likes", steps: 20_000);

        $progress->start();
        LazyCollection::times(200)->each(function () use ($post, $progress) {
            Like::factory(100)->for($post, "likeable")->create();
            $progress->advance(100);
        });
        $progress->finish();

        $post->increment("likes_count", 20_000);
    }
}
