<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Toast from '@/Components/Toast.vue'

defineOptions({ name: 'AdminLayout' })

const page = usePage()
const user = computed(() => (page.props as any).auth?.user)

interface NavItem {
    name: string
    href: string
    match: string
    icon: string
}

const navigation: NavItem[] = [
    {
        name: 'داشبورد',
        href: '/admin',
        match: '/admin',
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    },
    {
        name: 'کاربران',
        href: '/admin/users',
        match: '/admin/users',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        name: 'مدیریت آموزش‌ها',
        href: '/admin/trainings',
        match: '/admin/trainings',
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    },
    {
        name: 'تنظیمات',
        href: '/admin/settings',
        match: '/admin/settings',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    },
]

function isActive(item: NavItem): boolean {
    if (item.href === '/admin') {
        return page.url === '/admin' || page.url === '/admin/'
    }
    return page.url.startsWith(item.match)
}

const initial = computed(() => user.value?.first_name?.[0] ?? '?')
</script>

<template>
    <div dir="rtl" class="min-h-screen bg-slate-50 text-slate-800">
        <aside
            class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-slate-200 bg-white shadow-sm"
        >
            <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-5">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-500/30"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <div class="leading-tight">
                    <h1 class="text-sm font-bold text-slate-900">پنل مدیریت</h1>
                    <p class="text-[11px] text-slate-500">سیستم آموزش</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-3">
                <Link
                    v-for="item in navigation"
                    :key="item.href"
                    :href="item.href"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200"
                    :class="
                        isActive(item)
                            ? 'bg-indigo-50 text-indigo-700 shadow-sm'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                    "
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110"
                         :class="isActive(item) ? 'text-indigo-600' : 'text-slate-400'"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>

                    <span class="truncate">{{ item.name }}</span>

                    <span v-if="isActive(item)"
                          class="mr-auto h-1.5 w-1.5 rounded-full bg-indigo-500" />
                </Link>
            </nav>

            <div class="border-t border-slate-200 p-3">
                <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-linear-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white"
                    >
                        {{ initial }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-900">
                            {{ user?.first_name }} {{ user?.last_name }}
                        </p>
                        <p class="truncate text-xs text-slate-500">مدیر سیستم</p>
                    </div>
                </div>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    خروج از حساب
                </Link>
            </div>
        </aside>

        <div class="ml-64 flex min-h-screen flex-col">
            <header
                class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur"
            >
                <div class="flex h-16 items-center justify-between px-6">
                    <slot name="title">
                        <h2 class="text-lg font-semibold text-slate-900">پنل مدیریت</h2>
                    </slot>
                </div>
            </header>

            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>

        <Toast />
    </div>
</template>
