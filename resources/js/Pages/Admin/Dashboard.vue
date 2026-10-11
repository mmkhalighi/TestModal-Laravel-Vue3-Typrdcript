<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

defineOptions({ name: 'AdminDashboard' })

const page = usePage()
const user = computed(() => (page.props as any).auth?.user)

const stats = [
    {
        label: 'کاربران',
        value: '—',
        href: '/admin/users',
        color: 'from-indigo-500 to-violet-600',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        label: 'آموزش‌ها',
        value: '—',
        href: '/admin/trainings',
        color: 'from-emerald-500 to-teal-600',
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    },
    {
        label: 'سرفصل‌ها',
        value: '—',
        href: '/admin/trainings',
        color: 'from-amber-500 to-orange-600',
        icon: 'M4 6h16M4 10h16M4 14h16M4 18h16',
    },
]
</script>

<template>
    <Head title="داشبورد مدیریت" />

    <AdminLayout>
        <div class="space-y-6">
            <!-- خوش‌آمدگویی -->
            <div
                class="relative overflow-hidden rounded-2xl bg-linear-to-br from-indigo-600 via-indigo-700 to-violet-700 p-8 text-white shadow-xl"
            >
                <div
                    class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"
                />
                <div
                    class="absolute -bottom-16 left-24 h-48 w-48 rounded-full bg-white/10 blur-3xl"
                />

                <div class="relative">
                    <p class="text-sm text-indigo-200">
                        خوش آمدید 👋
                    </p>
                    <h1 class="mt-2 text-2xl font-bold sm:text-3xl">
                        {{ user?.first_name }} {{ user?.last_name }}
                    </h1>
                    <p class="mt-2 text-sm text-indigo-100">
                        به پنل مدیریت خوش آمدید. از منوی سمت چپ می‌توانید به بخش‌های مختلف دسترسی داشته باشید.
                    </p>
                </div>
            </div>

            <!-- کارت‌های آماری -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="item in stats"
                    :key="item.label"
                    :href="item.href"
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br text-white shadow-md transition-transform group-hover:scale-110"
                        :class="item.color"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                :d="item.icon"
                            />
                        </svg>
                    </div>

                    <p class="mt-4 text-sm font-medium text-slate-500">
                        {{ item.label }}
                    </p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">
                        {{ item.value }}
                    </p>
                </Link>
            </div>

            <!-- دسترسی سریع -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-slate-900">
                    دسترسی سریع
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    می‌توانید مستقیماً به یکی از بخش‌های مدیریتی بروید.
                </p>

                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <Link
                            href="/admin/trainings"
                            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700"
                        >
                            مدیریت آموزش‌ها
                        </Link>
                        <Link
                            href="/admin/users"
                            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                        >
                            مدیریت کاربران
                        </Link>
                    </div>
                    <Link
                        href="/admin/trainings"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                    >
                        مدیریت آموزش‌ها
                    </Link>
                    <Link
                        href="/admin/users"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                    >
                        مدیریت کاربران
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
