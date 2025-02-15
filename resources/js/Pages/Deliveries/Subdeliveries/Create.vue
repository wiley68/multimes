<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue';

const props = defineProps({
    products: Array,
    delivery_id: String,
})

const form = useForm({
    delivery_id: props.delivery_id,
    product: null,
    quantity: 1,
    price: 0.00,
})

const productsOptions = props.products?.map(product => ({
    label: product.nomenklature + ' - ' + product.name + ' - [' + product.type + ']',
    value: product.id,
    price: product.price,
    me: product.me,
}))

const onSubmit = () => {
    form.post(route('subdeliveries.store'))
}

const me = ref('')

watch(
    () => form.product,
    (newValue, oldValue) => {
        if (newValue && newValue.value !== oldValue?.value) {
            form.price = newValue.price
        }
        me.value = newValue.me
    }
)

const title = `Добавяне на продукт към доставка №${props.delivery_id}`
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
                                <q-form
                                    class="row q-gutter-xl"
                                    autofocus
                                >
                                    <q-select
                                        v-model="form.product"
                                        :options="productsOptions"
                                        class="col"
                                        label="Избери продукт *"
                                        hint="Избери продукт който да добавиш към доставката."
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.product"
                                    />

                                    <q-input
                                        v-model.number="form.quantity"
                                        class="col"
                                        type="number"
                                        label="Количество"
                                        hint="Количество от избрания продукт за добавяне към доставката."
                                        :error="form.hasErrors"
                                        :error-message="form.errors.quantity"
                                    >
                                        <template v-slot:append>
                                            <span class="text-subtitle1">{{ me }}</span>
                                        </template>
                                    </q-input>

                                    <q-input
                                        v-model.number="form.price"
                                        class="col"
                                        type="number"
                                        label="Цена *"
                                        hint="Единична цена на избрания продукт за добавяне към доставката."
                                        :error="form.hasErrors"
                                        :error-message="form.errors.price"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        @click.prevent="router.get(route('deliveries.edit', { delivery: delivery_id }))"
                        color="primary"
                        flat
                        label="Доставка"
                        icon="mdi-menu-left"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Добави продукта"
                        color="primary"
                        icon="mdi-plus"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
