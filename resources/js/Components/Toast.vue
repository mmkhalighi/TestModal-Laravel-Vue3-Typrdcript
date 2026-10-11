<script setup lang="ts">
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const visible = ref(false)
const message = ref('')
const type = ref<'success' | 'error'>('success')

let timer: ReturnType<typeof setTimeout> | null = null

watch(
    () => page.url,
    () => {
        const flash = (page.props as any).flash

        if (flash?.success) {
            message.value = flash.success
            type.value = 'success'
            visible.value = true
        } else if (flash?.error) {
            message.value = flash.error
            type.value = 'error'
            visible.value = true
        } else {
            return
        }

        if (timer) clearTimeout(timer)
        timer = setTimeout(() => {
            visible.value = false
        }, 4000)
    },
    { immediate: true },
)
</script>

<template>
    <Teleport to="body">
        <Transition name="toast">
            <div
                v-if="visible"
                dir="rtl"
                class="fixed bottom-6 left-6 z-100 flex max-w-sm items-start gap-3 rounded-xl px-4 py-3 shadow-xl ring-1 backdrop-blur"
                :class="
                    type === 'error'
                        ? 'bg-red-50/95 text-red-800 ring-red-200'
                        : 'bg-emerald-50/95 text-emerald-800 ring-emerald-200'
                "
            >
                <svg v-if="type === 'error'" xmlns="http://www.w3.org/2000/svg"
                     class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <p class="flex-1 text-sm font-medium">
                    {{ message }}
                </p>

                <button type="button" class="text-slate-400 hover:text-slate-600"
                        @click="visible = false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateY(12px);
}
</style>
