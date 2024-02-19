<?php

use App\Models\Post;
use Illuminate\Support\Str;

it("returns title case for titles", function () {
    $post = Post::factory()->make(["title" => "Hello, how are you?"]);

    expect($post->title)->toBe("Hello, How Are You?");
});

it("can generate a route to the show page", function () {
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(
        route("posts.show", [$post, Str::slug($post->title)])
    );
});

it("can generate additional parameters on the show route", function () {
    $post = Post::factory()->create();

    expect($post->showRoute(["page" => 2]))->toBe(
        route("posts.show", [$post, Str::slug($post->title), "page" => 2])
    );
});
