<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseModal from '@/Components/Modal.vue'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import UserForm from '@/Components/Form/UserForm.vue'
import { useModal } from '@/Composables/useModal'
import type { PaginatedData, User } from '@/Types/user'

defineOptions({ layout: AdminLayout })

defineProps<{ users: PaginatedData<User> }>()

const modal = useModal<User>()

const confirmDelete = ref({
    open: false,
    user: null as User | null,
    loading: false,
})

function onSuccess() {
    modal.close()
}

function askDelete(user: User) {
    confirmDelete.value = { open: true, user, loading: false }
}

function doDelete() {
    if (!confirmDelete.value.user) return

    confirmDelete.value.loading = true

    router.delete(`/admin/users/${confirmDelete.value.user.id}`, {
        preserveScroll: true,
        onFinish: () => {
            confirmDelete.value.open = false
            confirmDelete.value.loading = false
        },
    })
}
</script>

<template>
    <Head title="کاربران" />

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">کاربران</h1>
                <p class="mt-1 text-sm text-slate-500">
                    مدیریت کاربران سیستم
                </p>
            </div>

            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-700"
                @click="modal.openCreate()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4" />
                </svg>
                افزودن کاربر
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-4 font-semibold">نام</th>
                        <th class="px-6 py-4 font-semibold">نام خانوادگی</th>
                        <th class="px-6 py-4 font-semibold">کد</th>
                        <th class="px-6 py-4 font-semibold">نام کاربری</th>
                        <th class="px-6 py-4 font-semibold">نقش</th>
                        <th class="px-6 py-4 font-semibold">عملیات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    <tr v-for="user in users.data" :key="user.id"
                        class="transition hover:bg-slate-50/60">
                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ user.first_name }}
                        </td>
                        <td class="px-6 py-4">{{ user.last_name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ user.code }}</td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ user.username }}
                        </td>
                        <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-medium"
                                      :class="user.role === 'admin'
                                        ? 'bg-purple-100 text-purple-700'
                                        : 'bg-blue-100 text-blue-700'">
                                    {{ user.role === 'admin' ? 'مدیر' : 'کاربر' }}
                                </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200"
                                        @click="modal.openEdit(user)">
                                    ویرایش
                                </button>
                                <button type="button"
                                        class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100"
                                        @click="askDelete(user)">
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="users.data.length === 0">
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            هیچ کاربری ثبت نشده است.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <nav v-if="users.last_page > 1" class="flex flex-wrap justify-center gap-2">
            <Link v-for="page in users.last_page" :key="page"
                  :href="`/admin/users?page=${page}`" preserve-scroll
                  class="rounded-lg px-3 py-2 text-sm transition"
                  :class="page === users.current_page
                    ? 'bg-indigo-600 text-white shadow-md'
                    : 'bg-white text-slate-700 hover:bg-slate-100'">
                {{ page }}
            </Link>
        </nav>

        <BaseModal
            :show="modal.isOpen"
            :title="modal.mode === 'create' ? 'افزودن کاربر' : 'ویرایش کاربر'"
            max-width="max-w-2xl"
            @close="modal.close()"
        >
            <UserForm
                :mode="modal.mode"
                :user="modal.data"
                :open="modal.isOpen"
                @success="onSuccess"
            />

            <template #footer>
                <button type="button"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        @click="modal.close()">
                    انصراف
                </button>
                <button type="submit" form="user-form"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-md shadow-indigo-500/20 transition hover:bg-indigo-700">
                    {{ modal.mode === 'create' ? 'ثبت کاربر' : 'ذخیره تغییرات' }}
                </button>
            </template>
        </BaseModal>

        <ConfirmDialog
            :show="confirmDelete.open"
            :loading="confirmDelete.loading"
            title="حذف کاربر"
            :message="confirmDelete.user
                ? `آیا از حذف ${confirmDelete.user.first_name} ${confirmDelete.user.last_name} مطمئن هستید؟ این عملیات برگشت‌پذیر نیست.`
                : ''"
            confirm-text="بله، حذف کن"
            @confirm="doDelete"
            @cancel="confirmDelete.open = false"
        />
    </div>
</template>
