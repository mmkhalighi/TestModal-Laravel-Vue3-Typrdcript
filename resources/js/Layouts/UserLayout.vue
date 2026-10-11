<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Toast from '@/Components/Toast.vue'

defineOptions({ name: 'UserLayout' })

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
        name: 'آموزش‌ها',
        href: '/dashboard',
        match: '/dashboard',
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    },
    {
        name: 'علاقه‌مندی‌ها',
        href: '/favorites',
        match: '/favorites',
        icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
    },
    {
        name: 'تنظیمات',
        href: '/settings',
        match: '/settings',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    },
]

function isActive(item: NavItem): boolean {
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
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/30"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>

                <div class="leading-tight">
                    <h1 class="text-sm font-bold text-slate-900">آموزش</h1>
                    <p class="text-[11px] text-slate-500">پنل کاربری</p>
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
                            ? 'bg-emerald-50 text-emerald-700 shadow-sm'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                    "
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110"
                         :class="isActive(item) ? 'text-emerald-600' : 'text-slate-400'"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>

                    <span class="truncate">{{ item.name }}</span>

                    <span v-if="isActive(item)"
                          class="mr-auto h-1.5 w-1.5 rounded-full bg-emerald-500" />
                </Link>
            </nav>

            <div class="border-t border-slate-200 p-3">
                <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-linear-to-br from-emerald-500 to-teal-600 text-sm font-bold text-white"
                    >
                        {{ initial }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-900">
                            {{ user?.first_name }} {{ user?.last_name }}
                        </p>
                        <p class="truncate text-xs text-slate-500">{{ user?.username }}</p>
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
                        <h2 class="text-lg font-semibold text-slate-900">پنل کاربری</h2>
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
