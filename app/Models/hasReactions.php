<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait hasReactions
{
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, "reactionable");
    }

    public function likes(): MorphMany
    {
        return $this->reactions()->where("is_like", true);
    }

    public function dislikes(): MorphMany
    {
        return $this->reactions()->where("is_like", false);
    }
}
