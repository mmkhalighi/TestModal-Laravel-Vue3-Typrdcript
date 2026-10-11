<script setup lang="ts">
import { computed, onUnmounted, watch } from 'vue'

defineOptions({ name: 'BaseModal' })

const props = withDefaults(
    defineProps<{
        show: boolean
        title?: string
        maxWidth?: string
        closeable?: boolean
    }>(),
    {
        maxWidth: 'max-w-lg',
        closeable: true,
    },
)

const emit = defineEmits<{ close: [] }>()

const widthClass = computed(() => {
    if (!props.maxWidth) return 'max-w-lg'
    if (props.maxWidth.startsWith('max-w-')) return props.maxWidth
    return `max-w-${props.maxWidth}`
})

function handleClose() {
    if (props.closeable) emit('close')
}

function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'Escape') handleClose()
}

watch(
    () => props.show,
    (val) => {
        document.body.style.overflow = val ? 'hidden' : ''
        if (val) {
            window.addEventListener('keydown', handleKeydown)
        } else {
            window.removeEventListener('keydown', handleKeydown)
        }
    },
)

onUnmounted(() => {
    document.body.style.overflow = ''
    window.removeEventListener('keydown', handleKeydown)
})
</script>

<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
                role="dialog"
                aria-modal="true"
            >
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                    @click="handleClose"
                />

                <div
                    class="relative z-10 my-8 w-full rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5"
                    :class="widthClass"
                >
                    <header
                        v-if="title || $slots.header"
                        class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4"
                    >
                        <slot name="header">
                            <h2 class="text-lg font-bold text-slate-900">
                                {{ title }}
                            </h2>
                        </slot>

                        <button
                            v-if="closeable"
                            type="button"
                            class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                            @click="emit('close')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </header>

                    <div class="max-h-[calc(100vh-12rem)] overflow-y-auto px-6 py-5">
                        <slot />
                    </div>

                    <footer
                        v-if="$slots.footer"
                        class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4"
                    >
                        <slot name="footer" />
                    </footer>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

.modal-fade-enter-active > div:last-child,
.modal-fade-leave-active > div:last-child {
    transition: transform 0.2s ease;
}

.modal-fade-enter-from > div:last-child {
    transform: scale(0.95) translateY(8px);
}
</style>
