<?php

use App\Models\Reaction;

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
});

it("allows disliking previously liked reactionable", function () {
    $reaction = Reaction::factory()->create(["is_like" => Reaction::LIKE]);

    $user = $reaction->user;
    $reactionable = $reaction->reactionable;

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
});
