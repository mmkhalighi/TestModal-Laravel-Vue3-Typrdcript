import { reactive, ref } from 'vue'

export function useModal<T = unknown>() {
    const isOpen = ref(false)
    const mode = ref<'create' | 'edit'>('create')
    const data = ref<T | null>(null)

    function openCreate() {
        mode.value = 'create'
        data.value = null
        isOpen.value = true
    }

    function openEdit(payload: T) {
        mode.value = 'edit'
        data.value = payload
        isOpen.value = true
    }

    function close() {
        isOpen.value = false
        setTimeout(() => {
            data.value = null
        }, 200)
    }

    return reactive({
        isOpen,
        mode,
        data,
        openCreate,
        openEdit,
        close,
    })
}
