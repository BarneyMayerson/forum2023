<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\delete;
use function Pest\Laravel\withoutExceptionHandling;

it("requires authentication", function () {
    delete(route("reactions.destroy", ["post", 1, true]))->assertRedirect(
        route("login")
    );
});

it("allows unliking a reactionable", function (Model $reactionable) {
    $user = User::factory()->create();
    $reaction = Reaction::factory()
        ->for($user)
        ->for($reactionable, "reactionable")
        ->create(["is_like" => true]);

    actingAs($user)
        ->fromRoute("dashboard")
        ->delete(
            route("reactions.destroy", [
                $reactionable->getMorphClass(),
                $reactionable->id,
                true,
            ])
        )
        ->assertRedirect(route("dashboard"));

    assertDatabaseEmpty(Reaction::class);
    expect($reactionable->refresh()->likes_count)->toBe(0);
})->with([
    fn() => Post::factory()->create(["likes_count" => 1]),
    fn() => Comment::factory()->create(["likes_count" => 1]),
]);

// it("prevents unliking something you have not liked", function () {
//     $likeable = Post::factory()->create();

//     actingAs(User::factory()->create())
//         ->delete(
//             route("likes.destroy", [$likeable->getMorphClass(), $likeable->id])
//         )
//         ->assertForbidden();
// });

// it("only allows unliking supported models", function () {
//     $user = User::factory()->create();

//     actingAs($user)
//         ->delete(route("likes.destroy", [$user->getMorphClass(), $user->id]))
//         ->assertForbidden();
// });

// it("throws a 404 if the type is unsupported", function () {
//     actingAs(User::factory()->create())
//         ->delete(route("likes.destroy", ["foo", 1]))
//         ->assertNotFound();
// });
