```vue
<script setup lang="ts">
import { router } from '@inertiajs/vue3';

defineOptions({ name: 'UserTrainingNode' });

interface NodeData {
    id: number;
    title: string;
    video_url: string | null;
    is_favorite: boolean;
    children: NodeData[];
}

defineProps<{ node: NodeData }>();

function toggleFavorite(node: NodeData) {
    if (node.is_favorite) {
        router.delete(`/favorites/${node.id}`, {
            preserveScroll: true,
        });
    } else {
        router.put(`/favorites/${node.id}`, {}, {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <article class="rounded-xl border p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="font-semibold">{{ node.title }}</h3>

            <button
                type="button"
                @click="toggleFavorite(node)"
                class="rounded-lg border px-3 py-2 text-sm"
                :class="
          node.is_favorite
            ? 'border-amber-300 bg-amber-50 text-amber-800'
            : 'border-gray-300 hover:bg-gray-50'
        "
            >
                {{ node.is_favorite ? '★ حذف از علاقه‌مندی‌ها' : '☆ افزودن به علاقه‌مندی‌ها' }}
            </button>
        </div>

        <video
            v-if="node.video_url"
            :src="node.video_url"
            controls
            preload="metadata"
            class="mt-4 max-h-[480px] w-full rounded-lg bg-black"
        >
            مرورگر شما از پخش ویدئو پشتیبانی نمی‌کند.
        </video>

        <div
            v-if="node.children.length"
            class="mr-3 mt-4 space-y-3 border-r-2 border-gray-200 pr-3"
        >
            <UserTrainingNode
                v-for="child in node.children"
                :key="child.id"
                :node="child"
            />
        </div>
    </article>
</template>
```
