<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.product?.name,
    nomenklature: props.product?.nomenklature,
    description: props.product?.description,
    price: props.product?.price,
    stock: props.product?.stock,
    me: props.product?.me,
    type: props.product?.type,
})

const onSubmit = () => {
    form.put(route('products.update', props.product.id), {
        onFinish: () => {
            form.reset('name', 'nomenklature', 'description', 'price', 'me', 'stock', 'type')
        },
    })
}

const meOptions = [
    'бр', 'кг', 'л', 'м',
]

const typeOptions = [
    'Обща употреба', 'Процес угояване', 'Силоз угояване', 'Силоз майки',
]

const title = 'Продукт'
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
                        @click.prevent="onSubmit"
                        label="Запиши"
                        type="submit"
                        icon="mdi-content-save-outline"
                        color="primary"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>