<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it("requires authentication", function () {
    delete(
        route("comments.destroy", Comment::factory()->create())
    )->assertRedirect(route("login"));
});

it("can delete a comment", function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route("comments.destroy", $comment));

    $this->assertModelMissing($comment);
});

it("redirects to the post show page", function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route("comments.destroy", $comment))
        ->assertRedirect(route("posts.show", $comment->post_id));
});

it("prevets deleting a comment you did not create", function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route("comments.destroy", $comment))
        ->assertForbidden();
});

it("prevets deleting a comment posted over an hour ago", function () {
    $this->freezeTime();
    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    actingAs($comment->user)
        ->delete(route("comments.destroy", $comment))
        ->assertForbidden();
});