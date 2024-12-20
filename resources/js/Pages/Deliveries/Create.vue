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
                                style="width: 100%;"
                            >
                                <q-form
                                    class="row q-gutter-xl"
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
                        @click.prevent="router.get(route('deliveries.index'))"
                    />
                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши"
                        icon="mdi-content-save-outline"
                        color="primary"
                    />

                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>