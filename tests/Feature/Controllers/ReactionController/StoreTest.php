<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;
use function Pest\Laravel\withoutExceptionHandling;

it("requires authentication", function () {
    post(route("reactions.store", ["post", 1, true]))->assertRedirect(
        route("login")
    );
});

it("allows liking a reactionable", function (Model $reactionable) {
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute("dashboard")
        ->post(
            route("reactions.store", [
                $reactionable->getMorphClass(),
                $reactionable->id,
                Reaction::LIKE,
            ])
        )
        ->assertRedirect(route("dashboard"));

    assertDatabaseHas(Reaction::class, [
        "user_id" => $user->id,
        "reactionable_id" => $reactionable->id,
        "reactionable_type" => $reactionable->getMorphClass(),
        "is_like" => true,
    ]);

    expect($reactionable->refresh()->likes_count)->toBe(1);
})->with([
    fn() => Post::factory()->create(),
    fn() => Comment::factory()->create(),
]);

it("allows disliking a reactionable", function (Model $reactionable) {
    withoutExceptionHandling();
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute("dashboard")
        ->post(
            route("reactions.store", [
                $reactionable->getMorphClass(),
                $reactionable->id,
                Reaction::DISLIKE,
            ])
        )
        ->assertRedirect(route("dashboard"));

    assertDatabaseHas(Reaction::class, [
        "user_id" => $user->id,
        "reactionable_id" => $reactionable->id,
        "reactionable_type" => $reactionable->getMorphClass(),
        "is_like" => 0,
    ]);

    expect($reactionable->refresh()->dislikes_count)->toBe(1);
})->with([
    fn() => Post::factory()->create(),
    fn() => Comment::factory()->create(),
]);
