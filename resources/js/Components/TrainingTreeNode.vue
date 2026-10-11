<script setup lang="ts">
import type { TrainingNodeDraft } from '@/Types/training';

defineOptions({ name: 'TrainingTreeNode' });

defineProps<{
    node: TrainingNodeDraft;
    selectedId: string;
}>();

defineEmits<{
    select: [id: string];
}>();
</script>

<template>
    <div class="space-y-2">
        <button
            type="button"
            @click="$emit('select', node.id)"
            class="w-full rounded-lg border p-3 text-right transition"
            :class="selectedId === node.id
                ? 'border-indigo-600 bg-indigo-50 text-indigo-900'
                : 'border-gray-200 bg-white hover:bg-gray-50'"
        >
            {{ node.title }}
        </button>

        <div
            v-if="node.children.length"
            class="mr-6 space-y-2 border-r-2 border-gray-200 pr-4"
        >
            <TrainingTreeNode
                v-for="child in node.children"
                :key="child.id"
                :node="child"
                :selected-id="selectedId"
                @select="$emit('select', $event)"
            />
        </div>
    </div>
</template>
