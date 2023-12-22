<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $posts = Post::factory(150)
            ->has(Comment::factory(12)->recycle($users))
            ->recycle($users)->create();

        $barney = User::factory()
            ->has(Post::factory(42))
            ->has(Comment::factory(120)->recycle($posts))
            ->create([
                'name' => 'Barney Mayerson',
                'email' => 'mr.barney.mayerson@gmail.com',
                'password' => '$2y$12$lJq6Xm9CknrA7tQGtTK/zeAzJi34YmyI3gjen6C5aLMTaZdgX6RrS',
            ]);
    }
}
