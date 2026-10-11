```vue
<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

defineOptions({ layout: UserLayout });

interface Favorite {
    id: number;
    training_node_id: number;
    training_node: {
        id: number;
        title: string;
        training: {
            id: number;
            name: string;
        };
    } | null;
}

defineProps<{
    favorites: {
        data: Favorite[];
        links: { url: string | null; label: string; active: boolean }[];
    };
}>();

function removeFavorite(nodeId: number) {
    router.delete(`/favorites/${nodeId}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="علاقه‌مندی‌ها" />

    <main class="mx-auto max-w-5xl space-y-6 p-6" dir="rtl">
        <h1 class="text-2xl font-bold">علاقه‌مندی‌های من</h1>

        <div v-if="favorites.data.length" class="space-y-3">
            <article
                v-for="favorite in favorites.data"
                :key="favorite.id"
                class="flex flex-wrap items-center justify-between gap-4 rounded-xl border p-4"
            >
                <div v-if="favorite.training_node">
                    <h2 class="font-semibold">
                        {{ favorite.training_node.title }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        آموزش: {{ favorite.training_node.training.name }}
                    </p>

                    <a
                        :href="`/trainings/${favorite.training_node.training.id}`"
                        class="mt-2 inline-block text-sm text-blue-600 hover:underline"
                    >
                        مشاهده آموزش
                    </a>
                </div>

                <button
                    v-if="favorite.training_node"
                    type="button"
                    @click="removeFavorite(favorite.training_node_id)"
                    class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-700 hover:bg-red-50"
                >
                    حذف از علاقه‌مندی‌ها
                </button>
            </article>
        </div>

        <p v-else class="rounded-xl border p-5 text-gray-500">
            هنوز سرفصلی را به علاقه‌مندی‌ها اضافه نکرده‌ای.
        </p>

        <nav class="flex flex-wrap gap-2">
            <template v-for="link in favorites.links" :key="link.label">
                <a
                    v-if="link.url"
                    :href="link.url"
                    class="rounded border px-3 py-2 text-sm"
                    :class="link.active ? 'bg-blue-600 text-white' : 'bg-white'"
                    v-html="link.label"
                />
            </template>
        </nav>
    </main>
</template>
```
