<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReactionPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Model $reactionable): bool
    {
        if (!in_array($reactionable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $reactionable->reactions()->whereBelongsTo($user)->doesntExist();
    }

    /**
     * Determine whether the user can delete a like or dislike.
     */
    public function delete(User $user, Model $reactionable, bool $isLike): bool
    {
        if (!in_array($reactionable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $isLike
            ? $reactionable->likes()->whereBelongsTo($user)->exists()
            : $reactionable->dislikes()->whereBelongsTo($user)->exists();
    }

    /**
     * Determine whether the user can convert a like into a dislike.
     */
    public function update(User $user, Model $reactionable, bool $isLike): bool
    {
        if (!in_array($reactionable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $isLike
            ? $reactionable->dislikes()->whereBelongsTo($user)->exists()
            : $reactionable->likes()->whereBelongsTo($user)->exists();
    }
}
