import { reactive } from "vue";
import { router } from "@inertiajs/vue3";

const globalReactionable = reactive({
  type: "",
  id: "",
});

const create = (isLike) => {
  router.post(
    route("reactions.store", {
      type: globalReactionable.type,
      id: globalReactionable.id,
      is_like: isLike,
    }),
    null,
    {
      preserveScroll: true,
    },
  );
};

const remove = (isLike) => {
  router.delete(
    route("reactions.destroy", {
      type: globalReactionable.type,
      id: globalReactionable.id,
      is_like: isLike,
    }),
    {
      preserveScroll: true,
    },
  );
};

const update = (isLike) => {
  router.patch(
    route("reactions.update", {
      type: globalReactionable.type,
      id: globalReactionable.id,
      is_like: isLike,
    }),
    null,
    {
      preserveScroll: true,
    },
  );
};
export function useReaction() {
  return {
    like: (type, id) => {
      globalReactionable.type = type;
      globalReactionable.id = id;
      create(1);
    },

    unlike: (type, id) => {
      globalReactionable.type = type;
      globalReactionable.id = id;
      remove(1);
    },

    dislike: (type, id) => {
      globalReactionable.type = type;
      globalReactionable.id = id;
      create(0);
    },

    undislike: (type, id) => {
      globalReactionable.type = type;
      globalReactionable.id = id;
      remove(0);
    },

    toggleLikeToDislike: (type, id) => {
      globalReactionable.type = type;
      globalReactionable.id = id;
      update(0);
    },

    toggleDislikeToLike: (type, id) => {
      globalReactionable.type = type;
      globalReactionable.id = id;
      update(1);
    },
  };
}
