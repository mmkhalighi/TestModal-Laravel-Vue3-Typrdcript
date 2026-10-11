<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TrainingTreeViewer from '@/Components/TrainingTreeViewer.vue'

defineOptions({ layout: AdminLayout })

interface TrainingNode {
    id: number
    title: string
    video_url: string | null
    children: TrainingNode[]
}

defineProps<{
    training: { id: number; name: string; nodes: TrainingNode[] }
}>()
</script>

<template>
    <Head :title="training.name" />

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    {{ training.name }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    درخت آموزشی و ویدئوهای مربوط به هر عنوان
                </p>
            </div>

            <Link href="/admin/trainings"
                  class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                بازگشت به فهرست
            </Link>
        </div>

        <div class="rounded-2xl border border-indigo-100 bg-linear-to-br from-indigo-50 to-violet-50 p-5">
            <h2 class="font-bold text-indigo-950">{{ training.name }}</h2>
            <p class="mt-1 text-sm text-indigo-700">ریشهٔ اصلی آموزش</p>
        </div>

        <div class="space-y-4">
            <TrainingTreeViewer v-for="node in training.nodes" :key="node.id"
                                :node="node" />

            <p v-if="training.nodes.length === 0"
               class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400">
                این آموزش هنوز عنوانی ندارد.
            </p>
        </div>
    </div>
</template>
