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

const onReset = () => {
    form.reset('document', 'supplier', 'status')
}

const statusOptions = [
    { label: 'Типов документ', value: 0 },
    { label: 'Приключен документ', value: 1 },
]

const title = 'Промяна на Доставка'
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
                                style="width: 800px; max-width: 100%;"
                            >
                                <q-form
                                    @submit.prevent="onSubmit"
                                    @reset="onReset"
                                    class="q-gutter-md"
                                >
                                    <q-input
                                        v-model="form.document"
                                        label="Документ номер"
                                        hint="Номер на насрещния документ за доставка"
                                        autofocus
                                    />

                                    <q-input
                                        v-model="form.supplier"
                                        label="Доставчик"
                                        hint="Доставчик на продуктите"
                                    />

                                    <q-select
                                        v-model="form.status"
                                        :options="statusOptions"
                                        label="Тип документ *"
                                        hint="Тип на документа Типов/Приключен"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.status"
                                    />

                                    <div>
                                        <q-btn
                                            label="Промени"
                                            type="submit"
                                            color="primary"
                                        />
                                        <q-btn
                                            label="Откажи"
                                            type="reset"
                                            color="primary"
                                            flat
                                            class="q-ml-sm"
                                        />
                                    </div>
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Доставки"
                        icon="mdi-menu-left"
                        @click="router.get(route('deliveries.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>