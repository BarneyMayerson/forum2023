<?php

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return the correct component', function() {
    get(route('posts.index'))
        ->assertInertia(fn(AssertableInertia $inertia) => $inertia
            ->component('Posts/Index', true)
        );
});

it('passes posts to the view', function() {
    AssertableInertia::macro('hasResource', function(string $key, JsonResource $resource) {
        $props = $this->toArray()['props'];

        $compiledResource = $resource->response()->getData(true);

        expect($props)
            ->toHaveKey($key, message: "Key \"{$key}\" not passed as a property to Inertia.")
            ->and($props[$key])
            ->toEqual($compiledResource);

        return $this;
    });

    AssertableInertia::macro('hasPaginatedResource', function(string $key, ResourceCollection $resource) {
        $props = $this->toArray()['props'];

        $compiledResource = $resource->response()->getData(true);

        expect($props)
            ->toHaveKey($key, message: "Key \"{$key}\" not passed as a property to Inertia.")
            ->and($props[$key])
            ->toHaveKeys(['data', 'meta', 'links'])
            ->data
            ->toEqual($compiledResource);

        return $this;
    });

    $posts = Post::factory(3)->create();

    get(route('posts.index'))
        ->assertInertia(fn(AssertableInertia $inertia) => $inertia
            ->hasResource('post', PostResource::make($posts->first()))
            ->hasPaginatedResource('posts', PostResource::collection($posts->reverse()))
        );
});