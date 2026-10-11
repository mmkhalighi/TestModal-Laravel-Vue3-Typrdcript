<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import TrainingTreeNode from '@/Components/TrainingTreeNode.vue'
import type {
    TrainingNodeDraft,
    TrainingNodePayload,
} from '@/Types/training'

const props = defineProps<{
    mode: 'create' | 'edit'
    training: { id: number; name: string } | null
    initialNodes: TrainingNodeDraft[]
    open: boolean
}>()

const emit = defineEmits<{ success: [];}>()

const step = ref<'name' | 'tree'>('name')
const nameForm = useForm({ name: '' })
const trainingName = ref('')
const nodes = ref<TrainingNodeDraft[]>([])
const selectedId = ref<string>('root')
const saving = ref(false)
const saveErrors = ref<Record<string, string>>({})

interface FoundNode {
    node: TrainingNodeDraft
    siblings: TrainingNodeDraft[]
    index: number
    parent: TrainingNodeDraft | null
}

watch(
    () => props.open,
    (isOpen) => {
        if (!isOpen) return

        saveErrors.value = {}
        selectedId.value = 'root'

        if (props.mode === 'edit' && props.training) {
            const cloned = JSON.parse(
                JSON.stringify(props.initialNodes),
            ) as TrainingNodeDraft[]

            const resetFiles = (items: TrainingNodeDraft[]) => {
                items.forEach((n) => {
                    n.video = null
                    resetFiles(n.children)
                })
            }

            resetFiles(cloned)
            nodes.value = cloned
            trainingName.value = props.training.name
            step.value = 'tree'
        } else {
            nameForm.reset()
            nameForm.clearErrors()
            trainingName.value = ''
            nodes.value = []
            step.value = 'name'
        }
    },
    { immediate: true },
)

function findNode(
    items: TrainingNodeDraft[],
    id: string,
    parent: TrainingNodeDraft | null = null,
): FoundNode | null {
    for (let index = 0; index < items.length; index++) {
        const node = items[index]
        if (!node) continue

        if (node.id === id) {
            return { node, siblings: items, index, parent }
        }

        const found = findNode(node.children, id, node)
        if (found) return found
    }
    return null
}

function findSelectedNode(): TrainingNodeDraft | null {
    if (selectedId.value === 'root') return null
    return findNode(nodes.value, selectedId.value)?.node ?? null
}

function checkName() {
    nameForm.clearErrors()

    nameForm
        .transform((data) => ({ name: data.name.trim() }))
        .post('/admin/trainings/check-name', {
            preserveScroll: true,
            onSuccess: () => {
                trainingName.value = nameForm.name.trim()
                nodes.value = []
                saveErrors.value = {}
                selectedId.value = 'root'
                step.value = 'tree'
            },
        })
}

function createNode(): TrainingNodeDraft {
    return {
        id: crypto.randomUUID(),
        title: '',
        video: null,
        children: [],
    }
}

function addNode() {
    const newNode = createNode()

    if (selectedId.value === 'root') {
        nodes.value.push(newNode)
    } else {
        const found = findNode(nodes.value, selectedId.value)
        if (!found) {
            selectedId.value = 'root'
            return
        }
        found.node.children.push(newNode)
    }

    selectedId.value = newNode.id
}

function moveUp() {
    const found = findNode(nodes.value, selectedId.value)
    if (!found || found.index <= 0) return

    const [node] = found.siblings.splice(found.index, 1)
    if (!node) return
    found.siblings.splice(found.index - 1, 0, node)
}

function moveDown() {
    const found = findNode(nodes.value, selectedId.value)
    if (!found || found.index >= found.siblings.length - 1) return

    const [node] = found.siblings.splice(found.index, 1)
    if (!node) return
    found.siblings.splice(found.index + 1, 0, node)
}

function increaseDepth() {
    const found = findNode(nodes.value, selectedId.value)
    if (!found || found.index <= 0) return

    const previous = found.siblings[found.index - 1]
    if (!previous) return

    const [node] = found.siblings.splice(found.index, 1)
    if (!node) return
    previous.children.push(node)
}

function decreaseDepth() {
    const found = findNode(nodes.value, selectedId.value)
    if (!found || !found.parent) return

    const parentLocation = findNode(nodes.value, found.parent.id)
    if (!parentLocation) return

    const [node] = found.siblings.splice(found.index, 1)
    if (!node) return
    parentLocation.siblings.splice(parentLocation.index + 1, 0, node)
}

function deleteSelectedNode() {
    const found = findNode(nodes.value, selectedId.value)
    if (!found) return

    found.siblings.splice(found.index, 1)
    selectedId.value = found.parent?.id ?? 'root'
}

function toPayload(
    items: TrainingNodeDraft[],
): TrainingNodePayload[] {
    return items.map((node) => ({
        title: node.title.trim(),
        video: node.video,
        children: toPayload(node.children),
    }))
}

function hasEmptyTitle(items: TrainingNodeDraft[]): boolean {
    return items.some(
        (node) =>
            node.title.trim() === '' || hasEmptyTitle(node.children),
    )
}

function saveTree() {
    saveErrors.value = {}

    if (!trainingName.value.trim()) {
        saveErrors.value = { name: 'نام آموزش الزامی است.' }
        return
    }

    if (hasEmptyTitle(nodes.value)) {
        saveErrors.value = {
            nodes: 'لطفاً عنوان تمام بخش‌ها را وارد کنید.',
        }
        return
    }

    saving.value = true

    const payload = {
        name: trainingName.value.trim(),
        nodes: toPayload(nodes.value),
    }

    const options = {
        forceFormData: true,
        preserveScroll: true,
        onError: (errors: Record<string, string>) => {
            saveErrors.value = Object.fromEntries(
                Object.entries(errors).map(([key, value]) => [
                    key,
                    String(value ?? ''),
                ]),
            )
        },
        onSuccess: () => emit('success'),
        onFinish: () => {
            saving.value = false
        },
    }

    // ✅ اینجا as any اضافه شد
    if (props.mode === 'create') {
        router.post('/admin/trainings', payload as any, options)
    } else if (props.training) {
        router.put(
            `/admin/trainings/${props.training.id}`,
            payload as any,
            options,
        )
    }
}

const selectedNode = computed(() => findSelectedNode())

const canMoveUp = computed(() => {
    const found = findNode(nodes.value, selectedId.value)
    return !!found && found.index > 0
})

const canMoveDown = computed(() => {
    const found = findNode(nodes.value, selectedId.value)
    return !!found && found.index < found.siblings.length - 1
})

const canIncreaseDepth = computed(() => {
    const found = findNode(nodes.value, selectedId.value)
    return !!found && found.index > 0
})

const canDecreaseDepth = computed(() => {
    const found = findNode(nodes.value, selectedId.value)
    return !!found && !!found.parent
})

defineExpose({ saveTree })
</script>

<template>
    <div class="space-y-5">
        <!-- مرحله اول: بررسی نام (فقط در create) -->
        <form
            v-if="mode === 'create' && step === 'name'"
            class="space-y-4"
            @submit.prevent="checkName"
        >
            <div class="rounded-xl bg-indigo-50 p-4 text-sm text-indigo-800">
                ابتدا نام آموزش را وارد کنید. پس از بررسی یکتا بودن،
                می‌توانید درخت آموزشی را بسازید.
            </div>

            <div>
                <label
                    for="training-name-input"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    نام آموزش
                </label>
                <input
                    id="training-name-input"
                    v-model="nameForm.name"
                    type="text"
                    maxlength="255"
                    required
                    autocomplete="off"
                    placeholder="مثلاً: آموزش مقدماتی برنامه‌نویسی"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p
                    v-if="nameForm.errors.name"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ nameForm.errors.name }}
                </p>
            </div>
        </form>

        <!-- مرحله دوم: درخت (create) یا کل فرم (edit) -->
        <div v-else class="space-y-5">
            <div v-if="mode === 'edit'" class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">
                    نام آموزش
                </label>
                <input
                    v-model="trainingName"
                    type="text"
                    maxlength="255"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none transition focus:ring-4 focus:ring-indigo-100"
                />
                <p v-if="saveErrors.name" class="text-sm text-red-600">
                    {{ saveErrors.name }}
                </p>
            </div>

            <div
                v-else
                class="rounded-xl bg-indigo-50 p-4"
            >
                <p class="text-xs text-indigo-700">نام آموزش</p>
                <h3 class="mt-1 font-bold text-indigo-950">
                    {{ trainingName }}
                </h3>
            </div>

            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-indigo-700"
                    @click="addNode"
                >
                    افزودن عنوان
                </button>

                <button
                    type="button"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="selectedId === 'root' || !canIncreaseDepth"
                    @click="increaseDepth"
                >
                    افزایش عمق
                </button>

                <button
                    type="button"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="selectedId === 'root' || !canDecreaseDepth"
                    @click="decreaseDepth"
                >
                    کاهش عمق
                </button>

                <button
                    type="button"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="selectedId === 'root' || !canMoveUp"
                    @click="moveUp"
                >
                    انتقال به بالا
                </button>

                <button
                    type="button"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="selectedId === 'root' || !canMoveDown"
                    @click="moveDown"
                >
                    انتقال به پایین
                </button>

                <button
                    type="button"
                    class="rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="selectedId === 'root'"
                    @click="deleteSelectedNode"
                >
                    حذف عنوان
                </button>
            </div>

            <div class="grid gap-4 lg:grid-cols-5">
                <!-- درخت -->
                <div class="space-y-2 lg:col-span-3">
                    <button
                        type="button"
                        class="w-full rounded-lg border-2 border-indigo-200 bg-indigo-50 p-3 text-right text-sm font-bold text-indigo-950 transition hover:bg-indigo-100"
                        :class="{ 'ring-2 ring-indigo-500': selectedId === 'root' }"
                        @click="selectedId = 'root'"
                    >
                        ریشه: {{ trainingName || '—' }}
                    </button>

                    <div class="mr-4 space-y-2 border-r-2 border-slate-200 pr-3">
                        <TrainingTreeNode
                            v-for="node in nodes"
                            :key="node.id"
                            :node="node"
                            :selected-id="selectedId"
                            @select="selectedId = $event"
                        />
                    </div>

                    <p
                        v-if="nodes.length === 0"
                        class="rounded-lg border border-dashed border-slate-300 p-4 text-center text-xs text-slate-500"
                    >
                        هنوز عنوانی اضافه نشده است.
                    </p>
                </div>

                <!-- مشخصات گره انتخاب‌شده -->
                <div class="space-y-3 rounded-lg border border-slate-200 bg-slate-50 p-4 lg:col-span-2">
                    <h3 class="text-sm font-semibold text-slate-900">
                        مشخصات عنوان
                    </h3>

                    <template v-if="selectedNode">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                عنوان
                            </label>
                            <input
                                :value="selectedNode.title"
                                type="text"
                                maxlength="255"
                                placeholder="عنوان فصل یا درس"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none"
                                @input="
                                    selectedNode &&
                                        (selectedNode.title = (
                                            $event.target as HTMLInputElement
                                        ).value)
                                "
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                فایل ویدئو (اختیاری)
                            </label>
                            <input
                                type="file"
                                accept="video/mp4,video/webm,video/quicktime"
                                class="block w-full text-xs text-slate-600 file:ml-2 file:rounded-md file:border-0 file:bg-indigo-100 file:px-3 file:py-1.5"
                                @change="
                                    (e) => {
                                        const t = e.target as HTMLInputElement
                                        if (selectedNode)
                                            selectedNode.video =
                                                t.files?.[0] ?? null
                                    }
                                "
                            />
                            <p class="mt-1 text-[11px] text-slate-500">
                                MP4 / WebM / MOV — حداکثر ۵۰۰ مگابایت
                            </p>
                        </div>
                    </template>

                    <p v-else class="text-xs text-slate-500">
                        برای ویرایش، یک عنوان را از درخت انتخاب کنید.
                    </p>
                </div>
            </div>

            <div
                v-if="Object.keys(saveErrors).length"
                class="space-y-1 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700"
            >
                <p v-for="(msg, key) in saveErrors" :key="key">
                    {{ msg }}
                </p>
            </div>

            <button
                type="button"
                :disabled="saving"
                class="w-full rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-500/20 transition hover:bg-emerald-700 disabled:opacity-50"
                @click="saveTree"
            >
                {{ saving ? 'در حال ذخیره...' : 'ذخیره آموزش' }}
            </button>
        </div>

        <!-- دکمه بررسی نام در create -->
        <div
            v-if="mode === 'create' && step === 'name'"
            class="flex justify-end"
        >
            <button
                type="button"
                :disabled="nameForm.processing"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:opacity-50"
                @click="checkName"
            >
                {{
                    nameForm.processing
                        ? 'در حال بررسی...'
                        : 'بررسی و ادامه'
                }}
            </button>
        </div>
    </div>
</template>
