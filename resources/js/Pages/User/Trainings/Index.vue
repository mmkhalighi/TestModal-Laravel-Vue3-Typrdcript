```vue
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

defineOptions({ layout: UserLayout });

interface Training {
    id: number;
    name: string;
    nodes_count: number;
}

defineProps<{
    trainings: {
        data: Training[];
        links: { url: string | null; label: string; active: boolean }[];
    };
}>();
</script>

<template>
    <Head title="آموزش‌ها" />

    <main class="mx-auto max-w-6xl space-y-6 p-6" dir="rtl">
        <h1 class="text-2xl font-bold">آموزش‌های من</h1>

        <div
            v-if="trainings.data.length"
            class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
        >
            <article
                v-for="training in trainings.data"
                :key="training.id"
                class="rounded-xl border bg-white p-5 shadow-sm"
            >
                <h2 class="text-lg font-semibold">
                    {{ training.name }}
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    {{ training.nodes_count }} سرفصل
                </p>

                <a
                    :href="`/trainings/${training.id}`"
                    class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                    مشاهده آموزش
                </a>
            </article>
        </div>

        <p v-else class="text-gray-500">
            هنوز آموزشی ثبت نشده است.
        </p>

        <nav class="flex flex-wrap gap-2">
            <template v-for="link in trainings.links" :key="link.label">
                <a
                    v-if="link.url"
                    :href="link.url"
                    class="rounded border px-3 py-2 text-sm"
                    :class="link.active ? 'bg-blue-600 text-white' : 'bg-white'"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded border px-3 py-2 text-sm text-gray-400"
                    v-html="link.label"
                />
            </template>
        </nav>
    </main>
</template>
```
