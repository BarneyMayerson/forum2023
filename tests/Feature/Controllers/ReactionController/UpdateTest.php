<?php

use App\Models\Reaction;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\patch;

it("requires authentication", function () {
    patch(
        route("reactions.update", ["post", 1, Reaction::LIKE])
    )->assertRedirect(route("login"));
});

it("allows liking previously disliked reactionable", function () {
    $reaction = Reaction::factory()->create(["is_like" => Reaction::DISLIKE]);

    $user = $reaction->user;
    $reactionable = $reaction->reactionable;

    // forced increment dislikes count because we already have a dislike
    $reactionable->update(["dislikes_count" => 1]);

    actingAs($user)
        ->fromRoute("dashboard")
        ->patch(
            route("reactions.update", [
                $reaction->reactionable->getMorphClass(),
                $reaction->reactionable->id,
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
    expect($reactionable->dislikes_count)->toBe(0);
});

it("allows disliking previously liked reactionable", function () {
    $reaction = Reaction::factory()->create(["is_like" => Reaction::LIKE]);

    $user = $reaction->user;
    $reactionable = $reaction->reactionable;

    // forced increment likes count because we already have a like
    $reactionable->update(["likes_count" => 1]);

    actingAs($user)
        ->fromRoute("dashboard")
        ->patch(
            route("reactions.update", [
                $reaction->reactionable->getMorphClass(),
                $reaction->reactionable->id,
                Reaction::DISLIKE,
            ])
        )
        ->assertRedirect(route("dashboard"));

    assertDatabaseHas(Reaction::class, [
        "user_id" => $user->id,
        "reactionable_id" => $reactionable->id,
        "reactionable_type" => $reactionable->getMorphClass(),
        "is_like" => false,
    ]);
    expect($reactionable->refresh()->likes_count)->toBe(0);
    expect($reactionable->dislikes_count)->toBe(1);
});

it("prevents liking something disliked by another user", function () {
    $dislike = Reaction::factory()->create(["is_like" => Reaction::DISLIKE]);

    actingAs(User::factory()->create())
        ->patch(
            route("reactions.update", [
                $dislike->reactionable->getMorphClass(),
                $dislike->reactionable->id,
                Reaction::LIKE,
            ])
        )
        ->assertForbidden();
});

it("prevents disliking something liked by another user", function () {
    $like = Reaction::factory()->create(["is_like" => Reaction::LIKE]);

    actingAs(User::factory()->create())
        ->patch(
            route("reactions.update", [
                $like->reactionable->getMorphClass(),
                $like->reactionable->id,
                Reaction::DISLIKE,
            ])
        )
        ->assertForbidden();
});
