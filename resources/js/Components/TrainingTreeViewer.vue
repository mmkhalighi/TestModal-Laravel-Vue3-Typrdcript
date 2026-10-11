
<script setup lang="ts">
defineOptions({ name: 'TrainingTreeViewer' });

interface ViewerNode {
    id: number;
    title: string;
    video_url: string | null;
    children: ViewerNode[];
}

defineProps<{
    node: ViewerNode;
}>();
</script>

<template>
    <div class="space-y-3">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <h3 class="font-semibold text-gray-900">
                {{ node.title }}
            </h3>

            <div v-if="node.video_url" class="mt-4">
                <video
                    controls
                    preload="metadata"
                    class="max-h-[480px] w-full rounded-lg bg-black"
                >
                    <source :src="node.video_url" />
                    مرورگر شما از پخش این ویدئو پشتیبانی نمی‌کند.
                </video>
            </div>

            <p v-else class="mt-2 text-sm text-gray-400">
                برای این عنوان ویدئویی ثبت نشده است.
            </p>
        </div>

        <div
            v-if="node.children.length"
            class="mr-5 space-y-3 border-r-2 border-gray-200 pr-4"
        >
            <TrainingTreeViewer
                v-for="child in node.children"
                :key="child.id"
                :node="child"
            />
        </div>
    </div>
</template>
