<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, string $type, int $id, bool $isLike)
    {
        $reactionable = $this->findReactionable($type, $id);

        $this->authorize("create", [Reaction::class, $reactionable]);

        $reactionable->reactions()->create([
            "user_id" => $request->user()->id,
            "is_like" => $isLike,
        ]);

        $isLike
            ? $reactionable->increment("likes_count")
            : $reactionable->increment("dislikes_count");

        return back();
    }

    public function update(
        Request $request,
        string $type,
        int $id,
        bool $isLike
    ) {
        $reactionable = $this->findReactionable($type, $id);

        $this->authorize("update", [Reaction::class, $reactionable, $isLike]);

        $reactionable
            ->reactions()
            ->whereBelongsTo($request->user())
            ->first()
            ->toggle()
            ->save();

        if ($isLike) {
            $reactionable->increment("likes_count");
            $reactionable->decrement("dislikes_count");
        } else {
            $reactionable->decrement("likes_count");
            $reactionable->increment("dislikes_count");
        }

        return back();
    }

    public function destroy(
        Request $request,
        string $type,
        int $id,
        bool $isLike
    ) {
        $reactionable = $this->findReactionable($type, $id);

        $this->authorize("delete", [Reaction::class, $reactionable, $isLike]);

        $reactionable->reactions()->whereBelongsTo($request->user())->delete();

        $isLike
            ? $reactionable->decrement("likes_count")
            : $reactionable->decrement("dislikes_count");

        return back();
    }

    protected function findReactionable(string $type, int $id): Model
    {
        /** @var class-string<Model>|null $modelName */
        $modelName = Relation::getMorphedModel($type);

        if ($modelName === null) {
            throw new ModelNotFoundException();
        }

        $reactionable = $modelName::findOrFail($id);

        return $reactionable;
    }
}
