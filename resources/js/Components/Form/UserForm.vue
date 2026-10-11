<script setup lang="ts">
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import type { User, UserRole } from '@/Types/user'

const props = defineProps<{
    mode: 'create' | 'edit'
    user: User | null
    open: boolean
}>()

const emit = defineEmits<{ success: []; cancel: [] }>()

const form = useForm({
    first_name: '',
    last_name: '',
    code: '',
    username: '',
    password: '',
    password_confirmation: '',
    role: 'user' as UserRole,
})

watch(
    () => props.open,
    (isOpen) => {
        if (!isOpen) return

        form.clearErrors()

        if (props.mode === 'edit' && props.user) {
            form.first_name = props.user.first_name
            form.last_name = props.user.last_name
            form.code = props.user.code
            form.username = props.user.username
            form.role = props.user.role
            form.password = ''
            form.password_confirmation = ''
        } else {
            form.reset()
            form.role = 'user'
        }
    },
    { immediate: true },
)

function submit() {
    if (props.mode === 'create') {
        form.post('/admin/users', {
            preserveScroll: true,
            onSuccess: () => emit('success'),
        })
    } else if (props.user) {
        form.put(`/admin/users/${props.user.id}`, {
            preserveScroll: true,
            onSuccess: () => emit('success'),
        })
    }
}

defineExpose({ submit })
</script>

<template>
    <form id="user-form" class="space-y-5" @submit.prevent="submit">
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">نام</label>
                <input
                    v-model="form.first_name"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p v-if="form.errors.first_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.first_name }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">نام خانوادگی</label>
                <input
                    v-model="form.last_name"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p v-if="form.errors.last_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.last_name }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">کد</label>
                <input
                    v-model="form.code"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">
                    {{ form.errors.code }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">نام کاربری</label>
                <input
                    v-model="form.username"
                    type="text"
                    autocomplete="username"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p v-if="form.errors.username" class="mt-1 text-sm text-red-600">
                    {{ form.errors.username }}
                </p>
            </div>

            <div v-if="mode === 'create'">
                <label class="mb-2 block text-sm font-medium text-slate-700">رمز عبور</label>
                <input
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                    {{ form.errors.password }}
                </p>
            </div>

            <div v-if="mode === 'create'">
                <label class="mb-2 block text-sm font-medium text-slate-700">تکرار رمز عبور</label>
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">نقش</label>
                <select
                    v-model="form.role"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                >
                    <option value="user">کاربر عادی</option>
                    <option value="admin">مدیر</option>
                </select>
                <p v-if="form.errors.role" class="mt-1 text-sm text-red-600">
                    {{ form.errors.role }}
                </p>
            </div>
        </div>
    </form>
</template>
