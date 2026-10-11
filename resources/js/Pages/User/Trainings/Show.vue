```vue
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import UserTrainingNode from '@/Components/UserTrainingNode.vue';

defineOptions({ layout: UserLayout });

interface NodeData {
    id: number;
    title: string;
    video_url: string | null;
    is_favorite: boolean;
    children: NodeData[];
}

defineProps<{
    training: { id: number; name: string };
    nodes: NodeData[];
}>();
</script>

<template>
    <Head :title="training.name" />

    <main class="mx-auto max-w-5xl space-y-6 p-6" dir="rtl">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-bold">{{ training.name }}</h1>

            <a href="/dashboard" class="text-blue-600 hover:underline">
                بازگشت به آموزش‌ها
            </a>
        </div>

        <div v-if="nodes.length" class="space-y-4">
            <UserTrainingNode
                v-for="node in nodes"
                :key="node.id"
                :node="node"
            />
        </div>

        <p v-else class="rounded-xl border p-5 text-gray-500">
            هنوز سرفصلی برای این آموزش ثبت نشده است.
        </p>
    </main>
</template>
```
