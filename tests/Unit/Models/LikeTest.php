<?php

use App\Models\Like;

use function Pest\Laravel\withoutExceptionHandling;

it("can toggle like to dislike", function () {
    $like = Like::factory()->make(["is_like" => Like::LIKE]);

    expect($like->is_like)->toBeTruthy();
    expect($like->toggle()->is_like)->toBeFalsy();
});

it("can toggle dislike to like", function () {
    $like = Like::factory()->make(["is_like" => Like::DISLIKE]);

    expect($like->is_like)->toBeFalsy();
    expect($like->toggle()->is_like)->toBeTruthy();
});
