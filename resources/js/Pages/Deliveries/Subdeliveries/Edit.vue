<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
    subdelivery: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    delivery_id: props.subdelivery?.delivery_id,
    product: props.subdelivery?.product,
    quantity: props.subdelivery?.quantity,
    price: props.subdelivery?.price,
})

const onSubmit = () => {
    form.put(route('subdeliveries.update', props.subdelivery.id))
};

const me = ref('')

const title = `Промяна на продукт към доставка №${props.subdelivery.delivery_id}`
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
                                <q-form
                                    class="row q-gutter-xl"
                                    autofocus
                                >
                                    <q-input
                                        :model-value="subdelivery.product.name"
                                        class="col"
                                        label="Продукт"
                                        hint="Продукт добавен в доставката"
                                        readonly
                                    />

                                    <q-input
                                        v-model.number="form.quantity"
                                        class="col"
                                        type="number"
                                        label="Количество"
                                        hint="Количество от избрания продукт за добавяне към доставката."
                                        :error="form.hasErrors"
                                        :error-message="form.errors.quantity"
                                        autofocus
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
                        @click.prevent="router.get(route('deliveries.edit', { delivery: subdelivery.delivery_id }))"
                        color="primary"
                        flat
                        label="Доставка"
                        icon="mdi-menu-left"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши промените"
                        color="primary"
                        icon="mdi-content-save-outline"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
