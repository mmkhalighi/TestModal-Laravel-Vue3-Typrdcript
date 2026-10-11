<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    username: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/login', {
        onFinish: () => {
            form.password = ''
        },
    })
}
</script>

<template>
    <Head title="ورود" />

    <div
        dir="rtl"
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-linear-to-br from-slate-900 via-indigo-950 to-slate-900 px-4"
    >
        <!-- پس‌زمینه تزئینی -->
        <div class="pointer-events-none absolute -left-24 -top-24 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl" />
        <div class="pointer-events-none absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-violet-500/20 blur-3xl" />

        <div class="relative w-full max-w-md">
            <!-- کارت ورود -->
            <div class="rounded-3xl border border-white/10 bg-white/95 p-8 shadow-2xl backdrop-blur-xl">
                <div class="mb-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-500/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>

                    <h1 class="mt-5 text-2xl font-bold text-slate-900">
                        ورود به سیستم
                    </h1>
                    <p class="mt-2 text-sm text-slate-500">
                        برای ادامه، نام کاربری و رمز عبور خود را وارد کنید
                    </p>
                </div>

                <form class="space-y-5" @submit.prevent="submit">
                    <div>
                        <label
                            for="username"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            نام کاربری
                        </label>

                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            autocomplete="username"
                            autofocus
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:ring-4 focus:ring-indigo-100"
                            :class="{ 'border-red-500 ring-2 ring-red-100': form.errors.username }"
                        />

                        <p
                            v-if="form.errors.username"
                            class="mt-1.5 text-sm text-red-600"
                        >
                            {{ form.errors.username }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            رمز عبور
                        </label>

                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:ring-4 focus:ring-indigo-100"
                            :class="{ 'border-red-500 ring-2 ring-red-100': form.errors.password }"
                        />

                        <p
                            v-if="form.errors.password"
                            class="mt-1.5 text-sm text-red-600"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        />
                        مرا به خاطر بسپار
                    </label>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-br from-indigo-600 to-violet-600 px-4 py-3 font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:from-indigo-700 hover:to-violet-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        {{ form.processing ? 'در حال ورود...' : 'ورود' }}
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                © {{ new Date().getFullYear() }} سیستم آموزش
            </p>
        </div>
    </div>
</template>
