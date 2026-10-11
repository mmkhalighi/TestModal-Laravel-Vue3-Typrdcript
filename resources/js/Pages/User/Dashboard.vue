<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import UserLayout from '@/Layouts/UserLayout.vue'

defineOptions({ name: 'UserDashboard' })

const page = usePage()
const user = computed(() => (page.props as any).auth?.user)
</script>

<template>
    <Head title="آموزش‌ها" />

    <UserLayout>
        <div class="space-y-6">
            <!-- خوش‌آمدگویی -->
            <div
                class="relative overflow-hidden rounded-2xl bg-linear-to-br from-emerald-500 via-teal-600 to-cyan-600 p-8 text-white shadow-xl"
            >
                <div
                    class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"
                />
                <div
                    class="absolute -bottom-16 left-24 h-48 w-48 rounded-full bg-white/10 blur-3xl"
                />

                <div class="relative">
                    <p class="text-sm text-emerald-100">
                        خوش آمدید 👋
                    </p>
                    <h1 class="mt-2 text-2xl font-bold sm:text-3xl">
                        {{ user?.first_name }} {{ user?.last_name }}
                    </h1>
                    <p class="mt-2 text-sm text-emerald-50">
                        از منوی سمت چپ می‌توانید به دوره‌ها، علاقه‌مندی‌ها و تنظیمات دسترسی داشته باشید.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <Link
                            href="/dashboard"
                            class="rounded-xl bg-white px-5 py-2.5 text-sm font-medium text-emerald-700 shadow-md transition hover:bg-emerald-50"
                        >
                            مشاهده دوره‌ها
                        </Link>
                        <Link
                            href="/favorites"
                            class="rounded-xl border border-white/40 bg-white/10 px-5 py-2.5 text-sm font-medium text-white backdrop-blur transition hover:bg-white/20"
                        >
                            علاقه‌مندی‌ها
                        </Link>
                    </div>
                </div>
            </div>

            <!-- کارت‌های دسترسی سریع -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    href="/dashboard"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-teal-600 text-white shadow-md transition-transform group-hover:scale-110"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-900">دوره‌های من</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        مشاهده و شرکت در دوره‌های آموزشی
                    </p>
                </Link>

                <Link
                    href="/favorites"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-amber-500 to-orange-600 text-white shadow-md transition-transform group-hover:scale-110"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-900">علاقه‌مندی‌ها</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        سرفصل‌های نشان‌شده شما
                    </p>
                </Link>

                <Link
                    href="/settings"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-indigo-300 hover:shadow-lg"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-indigo-500 to-violet-600 text-white shadow-md transition-transform group-hover:scale-110"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-900">تنظیمات</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        تغییر رمز عبور و تنظیمات حساب
                    </p>
                </Link>
            </div>
        </div>
    </UserLayout>
</template>
