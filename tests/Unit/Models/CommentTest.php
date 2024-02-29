<?php

use App\Models\Comment;

it("generates the html", function () {
    $comment = Comment::factory()->make(["body" => "## Hi There"]);

    $comment->save();

    expect($comment->html)->toEqual(str($comment->body)->markdown());
});
