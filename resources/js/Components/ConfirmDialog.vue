<script setup lang="ts">
import BaseModal from './Modal.vue'

withDefaults(
    defineProps<{
        show: boolean
        title?: string
        message?: string
        confirmText?: string
        loading?: boolean
    }>(),
    {
        title: 'تأیید عملیات',
        message: 'آیا از انجام این عملیات مطمئن هستید؟',
        confirmText: 'حذف',
        loading: false,
    },
)

const emit = defineEmits<{ confirm: []; cancel: [] }>()
</script>

<template>
    <BaseModal
        :show="show"
        :title="title"
        max-width="max-w-md"
        :closeable="!loading"
        @close="emit('cancel')"
    >
        <div class="flex items-start gap-4">
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
            </div>

            <p class="flex-1 text-sm leading-6 text-slate-600">
                {{ message }}
            </p>
        </div>

        <template #footer>
            <button
                type="button"
                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                :disabled="loading"
                @click="emit('cancel')"
            >
                انصراف
            </button>
            <button
                type="button"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-md shadow-red-500/20 transition hover:bg-red-700 disabled:opacity-50"
                :disabled="loading"
                @click="emit('confirm')"
            >
                {{ loading ? 'در حال انجام...' : confirmText }}
            </button>
        </template>
    </BaseModal>
</template>
