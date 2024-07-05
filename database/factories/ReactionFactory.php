<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            "user_id" => User::factory([
                "email" => "test+" . Str::uuid() . "@example.com",
            ]),
            "reactionable_type" => $this->reactionableType(...),
            "reactionable_id" => Post::factory(),
            "is_like" => true,
        ];
    }

    public function dislike(): static
    {
        return $this->state(function (array $attributes) {
            return [
                "is_like" => false,
            ];
        });
    }

    protected function reactionableType(array $values)
    {
        $type = $values["reactionable_id"];
        $modelName =
            $type instanceof Factory ? $type->modelName() : $type::class;

        return (new $modelName())->getMorphClass();
    }
}
