<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseModal from '@/Components/Modal.vue'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import TrainingForm from '@/Components/Form/TrainingForm.vue'
import { useModal } from '@/Composables/useModal'
import type { TrainingNodeDraft } from '@/Types/training'

defineOptions({ layout: AdminLayout })

interface Training {
    id: number
    name: string
    nodes_count: number
    nodes?: TrainingNodeDraft[]
}

interface PaginatedTrainings {
    data: Training[]
    links: { url: string | null; label: string; active: boolean }[]
}

const props = defineProps<{ trainings: PaginatedTrainings }>()

const modal = useModal<Training>()
const editingNodes = ref<TrainingNodeDraft[]>([])

const confirmDelete = ref({
    open: false,
    training: null as Training | null,
    loading: false,
})

function openCreate() {
    editingNodes.value = []
    modal.openCreate()
}

function openEdit(training: Training) {
    editingNodes.value = (training.nodes ?? []) as TrainingNodeDraft[]
    modal.openEdit(training)
}

function onSuccess() {
    modal.close()
}

function askDelete(training: Training) {
    confirmDelete.value = { open: true, training, loading: false }
}

function doDelete() {
    if (!confirmDelete.value.training) return

    confirmDelete.value.loading = true

    router.delete(
        `/admin/trainings/${confirmDelete.value.training.id}`,
        {
            preserveScroll: true,
            onFinish: () => {
                confirmDelete.value.open = false
                confirmDelete.value.loading = false
            },
        },
    )
}
</script>

<template>
    <Head title="فهرست آموزش‌ها" />

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">آموزش‌ها</h1>
                <p class="mt-1 text-sm text-slate-500">
                    مشاهده و مدیریت آموزش‌های ثبت‌شده
                </p>
            </div>

            <button type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-700"
                    @click="openCreate">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4" />
                </svg>
                ایجاد آموزش جدید
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div v-if="trainings.data.length === 0" class="p-12 text-center text-slate-400">
                هنوز آموزشی ثبت نشده است.
            </div>

            <table v-else class="w-full text-right text-sm">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-6 py-4 font-semibold">نام آموزش</th>
                    <th class="px-6 py-4 font-semibold">تعداد عنوان‌ها</th>
                    <th class="px-6 py-4 font-semibold">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <tr v-for="training in trainings.data" :key="training.id"
                    class="transition hover:bg-slate-50/60">
                    <td class="px-6 py-4 font-medium text-slate-900">
                        {{ training.name }}
                    </td>
                    <td class="px-6 py-4">
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                                {{ training.nodes_count }} عنوان
                            </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <Link :href="`/admin/trainings/${training.id}`"
                                  class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200">
                                مشاهده
                            </Link>
                            <button type="button"
                                    class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                                    @click="openEdit(training)">
                                ویرایش
                            </button>
                            <button type="button"
                                    class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100"
                                    @click="askDelete(training)">
                                حذف
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <nav v-if="trainings.links.length > 3"
             class="flex flex-wrap justify-center gap-2">
            <Link v-for="link in trainings.links" :key="link.label"
                  :href="link.url ?? '#'" preserve-scroll
                  class="rounded-lg border border-slate-200 px-3 py-2 text-sm transition"
                  :class="{
                    'bg-indigo-600 text-white shadow-md': link.active,
                    'bg-white text-slate-700 hover:bg-slate-100': !link.active,
                    'pointer-events-none opacity-40': !link.url,
                }">
                <span v-html="link.label" />
            </Link>
        </nav>

        <BaseModal
            :show="modal.isOpen"
            :title="modal.mode === 'create' ? 'ایجاد آموزش' : 'ویرایش آموزش'"
            max-width="max-w-5xl"
            @close="modal.close()"
        >
            <TrainingForm
                :mode="modal.mode"
                :training="modal.data
                    ? { id: modal.data.id, name: modal.data.name }
                    : null"
                :initial-nodes="editingNodes"
                :open="modal.isOpen"
                @success="onSuccess"
            />
        </BaseModal>

        <ConfirmDialog
            :show="confirmDelete.open"
            :loading="confirmDelete.loading"
            title="حذف آموزش"
            :message="confirmDelete.training
                ? `آیا از حذف آموزش «${confirmDelete.training.name}» و تمام ویدئوهای آن مطمئن هستید؟ این عملیات برگشت‌پذیر نیست.`
                : ''"
            confirm-text="بله، حذف کن"
            @confirm="doDelete"
            @cancel="confirmDelete.open = false"
        />
    </div>
</template>
