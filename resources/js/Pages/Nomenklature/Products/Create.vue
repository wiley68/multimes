<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const props = defineProps({
    typeOptions: {
        type: Array,
    },
})

const form = useForm({
    name: '',
    nomenklature: '',
    description: '',
    price: '',
    stock: 0.00,
    me: 'бр',
    type: 'Обща употреба',
})

const $q = useQuasar()
const productsStore = () => {
    form.post(route('products.store'), {
        onFinish: () => {
            form.reset('name', 'nomenklature', 'description', 'price')
            form.me = 'бр'
            form.stock = 0
            form.type = 'Обща употреба'
        },
        onError: errors => {
            Object.values(errors).flat().forEach((error) => {
                $q.notify({
                    message: error,
                    icon: 'mdi-alert-circle-outline',
                    type: 'negative',
                });
            });
        },
    })
};

const meOptions = [
    'бр', 'кг', 'л', 'м',
]

const title = 'Продукт'
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
                            <q-card class="q-pa-md full-width">
                                <q-form class="q-gutter-md">
                                    <q-input
                                        v-model="form.name"
                                        label="Продукт *"
                                        hint="Име на продукта"
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />

                                    <q-input
                                        v-model="form.nomenklature"
                                        label="Номенклатура *"
                                        hint="Номенклатура на продукта"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.nomenklature"
                                    />

                                    <q-select
                                        v-model="form.type"
                                        :options="typeOptions"
                                        label="Предназначение *"
                                        hint="Предназначение на продукта"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.type"
                                    />

                                    <q-input
                                        v-model="form.description"
                                        autogrow
                                        label="Описание"
                                        hint="Описание на продукта"
                                    />

                                    <q-input
                                        v-model.number="form.price"
                                        type="number"
                                        label="Цена *"
                                        hint="Цена на продукта"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.price"
                                    />

                                    <q-input
                                        v-model.number="form.stock"
                                        type="number"
                                        label="Наличност"
                                        hint="Наличност на продукта"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.stock"
                                    />

                                    <q-select
                                        v-model="form.me"
                                        :options="meOptions"
                                        label="м.е. *"
                                        hint="Мерна единица на продукта"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.me"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Продукти"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('products.index'))"
                    />

                    <q-btn
                        @click.prevent="productsStore"
                        label="Запиши"
                        color="primary"
                        icon="mdi-content-save-outline"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
