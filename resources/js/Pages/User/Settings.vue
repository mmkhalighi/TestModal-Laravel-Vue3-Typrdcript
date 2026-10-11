<script setup lang="ts">
import UserLayout from '@/Layouts/UserLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
interface SharedProps {
    flash: {
        success?: string
        error?: string
    }
}
const page = usePage()

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.put('/settings/password', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    })
}
</script>

<template>
    <Head title="تنظیمات" />

    <UserLayout>
        <div dir="rtl" class="mx-auto max-w-2xl">
            <h1 class="mb-6 text-2xl font-bold text-gray-900">
                تنظیمات حساب کاربری
            </h1>

            <div
                v-if="page.props.flash?.success"
                class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-700"
            >
                {{ page.props.flash.success }}
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-5 rounded-xl border border-gray-200 bg-white p-6"
            >
                <h2 class="text-lg font-semibold">
                    تغییر رمز عبور
                </h2>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        رمز عبور فعلی
                    </label>

                    <input
                        v-model="form.current_password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    />

                    <p
                        v-if="form.errors.current_password"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ form.errors.current_password }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        رمز عبور جدید
                    </label>

                    <input
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    />

                    <p
                        v-if="form.errors.password"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        تکرار رمز عبور جدید
                    </label>

                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-5 py-3 font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {{ form.processing ? 'در حال ذخیره...' : 'تغییر رمز عبور' }}
                </button>
            </form>
        </div>
    </UserLayout>
</template>
