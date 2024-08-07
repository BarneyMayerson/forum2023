<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
    ]);
});

Route::middleware([
    "auth",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/dashboard", function () {
        return Inertia::render("Dashboard");
    })->name("dashboard");

    Route::resource("posts", PostController::class)->only(["create", "store"]);

    Route::resource("posts.comments", CommentController::class)
        ->shallow()
        ->only(["store", "update", "destroy"]);

    // Reactions: lakes & dislikes
    Route::post("reactions/{type}/{id}/{is_like}", [
        ReactionController::class,
        "store",
    ])->name("reactions.store");
    Route::patch("reactions/{type}/{id}/{is_like}", [
        ReactionController::class,
        "update",
    ])->name("reactions.update");
    Route::delete("reactions/{type}/{id}/{is_like}", [
        ReactionController::class,
        "destroy",
    ])->name("reactions.destroy");
});

Route::get("posts/{topic?}", [PostController::class, "index"])->name(
    "posts.index"
);
Route::get("posts/{post}/{slug}", [PostController::class, "show"])->name(
    "posts.show"
);
