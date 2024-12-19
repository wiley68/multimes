<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const form = useForm({
    document: '',
    supplier: '',
    status: { label: 'Типов документ', value: 0 },
})

const onSubmit = () => {
    form.post(route('deliveries.store'), {
        onFinish: () => {
            form.reset('document', 'supplier')
            form.status = { label: 'Типов документ', value: 0 }
        },
    })
};

const onReset = () => {
    form.reset('document', 'supplier')
    form.status = { label: 'Типов документ', value: 0 }
}

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
        icon="mdi-file-document-plus-outline"
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
                                            label="Създай"
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
                        label="Продукти"
                        icon="mdi-menu-left"
                        @click="router.get(route('deliveries.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>