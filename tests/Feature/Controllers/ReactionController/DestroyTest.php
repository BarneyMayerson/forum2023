<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\delete;

it("requires authentication", function () {
    delete(route("reactions.destroy", ["post", 1, true, true]))->assertRedirect(
        route("login")
    );
});

it("allows unliking a reactionable", function (Model $reactionable) {
    $user = User::factory()->create();
    Reaction::factory()
        ->for($user)
        ->for($reactionable, "reactionable")
        ->create(["is_like" => Reaction::LIKE]);

    actingAs($user)
        ->fromRoute("dashboard")
        ->delete(
            route("reactions.destroy", [
                $reactionable->getMorphClass(),
                $reactionable->id,
                Reaction::LIKE,
            ])
        )
        ->assertRedirect(route("dashboard"));

    assertDatabaseEmpty(Reaction::class);
    expect($reactionable->refresh()->likes_count)->toBe(0);
})->with([
    fn() => Post::factory()->create(["likes_count" => 1]),
    fn() => Comment::factory()->create(["likes_count" => 1]),
]);

it("allows undisliking a reactionable", function (Model $reactionable) {
    $user = User::factory()->create();
    Reaction::factory()
        ->for($user)
        ->for($reactionable, "reactionable")
        ->create(["is_like" => Reaction::DISLIKE]);

    actingAs($user)
        ->fromRoute("dashboard")
        ->delete(
            route("reactions.destroy", [
                $reactionable->getMorphClass(),
                $reactionable->id,
                Reaction::DISLIKE,
            ])
        )
        ->assertRedirect(route("dashboard"));

    assertDatabaseEmpty(Reaction::class);
    expect($reactionable->refresh()->dislikes_count)->toBe(0);
})->with([
    fn() => Post::factory()->create(["dislikes_count" => 1]),
    fn() => Comment::factory()->create(["dislikes_count" => 1]),
]);

it("prevents unliking something you have not liked", function () {
    $likeable = Post::factory()->create();

    actingAs(User::factory()->create())
        ->delete(
            route("reactions.destroy", [
                $likeable->getMorphClass(),
                $likeable->id,
                Reaction::LIKE,
            ])
        )
        ->assertForbidden();
});

it("prevents undisliking something you have not disliked", function () {
    $dislikeable = Post::factory()->create();

    actingAs(User::factory()->create())
        ->delete(
            route("reactions.destroy", [
                $dislikeable->getMorphClass(),
                $dislikeable->id,
                Reaction::DISLIKE,
            ])
        )
        ->assertForbidden();
});

it("only allows unliking supported models", function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(
            route("reactions.destroy", [
                $user->getMorphClass(),
                $user->id,
                Reaction::LIKE,
            ])
        )
        ->assertForbidden();
});

it("only allows undisliking supported models", function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(
            route("reactions.destroy", [
                $user->getMorphClass(),
                $user->id,
                Reaction::DISLIKE,
            ])
        )
        ->assertForbidden();
});

it("throws a 404 if the type is unsupported", function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route("reactions.destroy", ["foo", 1, Reaction::LIKE]))
        ->assertNotFound();

    actingAs($user)
        ->delete(route("reactions.destroy", ["foo", 1, Reaction::DISLIKE]))
        ->assertNotFound();
});
