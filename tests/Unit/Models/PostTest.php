<?php

use App\Models\Post;

it("returns title case for titles", function () {
    $post = Post::factory()->make(["title" => "Hello, how are you?"]);

    expect($post->title)->toBe("Hello, How Are You?");
});
