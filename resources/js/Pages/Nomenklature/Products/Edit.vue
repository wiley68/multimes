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
})

const onSubmit = () => {
    form.put(route('products.update', props.product.id), {
        onFinish: () => {
            form.reset('name', 'nomenklature', 'description', 'price', 'me', 'stock')
        },
    })
};

const onReset = () => {
    form.reset('name', 'nomenklature', 'description', 'price', 'me', 'stock')
}

const meOptions = [
    'бр', 'кг', 'л', 'м',
]

const title = 'Промяна на Продукт'
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
                        label="Продукти"
                        icon="mdi-menu-left"
                        @click="router.get(route('products.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>