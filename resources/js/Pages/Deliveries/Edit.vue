<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    delivery: {
        type: Object,
        required: true
    }
})

const form = useForm({
    document: props.delivery?.document,
    supplier: props.delivery?.supplier,
    status: props.delivery?.status === 0 ? { label: 'Типов документ', value: 0 } : { label: 'Приключен документ', value: 1 },
})

const onSubmit = () => {
    form.put(route('deliveries.update', props.delivery.id), {
        onFinish: () => {
            form.reset('document', 'supplier', 'status')
        },
    })
};

const statusOptions = [
    { label: 'Типов документ', value: 0 },
    { label: 'Приключен документ', value: 1 },
]

const title = 'Доставка'
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-edit-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <div class="column flex-grow flex-center">
                            <q-card
                                class="q-pa-md"
                                style="width: 100%;"
                            >
                                <q-form
                                    class="row q-gutter-md"
                                    autofocus
                                >
                                    <q-input
                                        v-model="form.document"
                                        class="col"
                                        label="Документ номер"
                                        hint="Номер на насрещния документ за доставка"
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.document"
                                    />

                                    <q-input
                                        v-model="form.supplier"
                                        class="col"
                                        label="Доставчик"
                                        hint="Доставчик на продуктите"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Доставки"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('deliveries.index'))"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши"
                        icon="mdi-content-save-outline"
                        color="primary"
                    />

                    <q-btn
                        label="Добави продукт"
                        icon="mdi-table-row-plus-after"
                        color="secondary"
                    />

                    <q-btn
                        label="Приключи документа"
                        icon="mdi-file-document-check-outline"
                        color="negative"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>