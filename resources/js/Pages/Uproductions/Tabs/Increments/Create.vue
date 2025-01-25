<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    uproduction: {
        type: Object,
        required: true,
    },
    udecrements: {
        type: Array,
        required: true,
    },
    uincrements: {
        type: Array,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
})

const form = useForm({
    uproduction_id: props.uproduction.id,
    product: props.product,
    quantity: 1,
    price: props.uproduction.price,
    status: 0,
    type: props.type,
})

const $q = useQuasar()
const onSubmit = () => {
    form.post(route('uincrements.store'), {
        onFinish: () => {
            form.reset('quantity')
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
}

const title = `Добавяне на приход към Процес №${props.uproduction.id}`
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
                                    <q-input
                                        :model-value="`[${product.nomenklature}] ${product.name}`"
                                        class="col"
                                        label="Продукт"
                                        hint="Продукт избран в прихода"
                                        readonly
                                    />

                                    <q-input
                                        v-model.number="form.quantity"
                                        class="col"
                                        type="number"
                                        label="Количество"
                                        hint="Количество от избрания продукт за приход."
                                        :error="form.hasErrors"
                                        :error-message="form.errors.quantity"
                                    >
                                        <template v-slot:append>
                                            <span class="text-subtitle1">{{ product.me }}</span>
                                        </template>
                                    </q-input>

                                    <q-input
                                        v-model.number="form.price"
                                        class="col"
                                        type="number"
                                        label="Цена *"
                                        hint="Единична цена на избрания продукт за приход."
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
                        @click.prevent="router.get(route('uproductions.show', uproduction.id))"
                        color="primary"
                        flat
                        :label="`Процес №${uproduction.id}`"
                        icon="mdi-menu-left"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Добави прихода"
                        color="primary"
                        icon="mdi-plus"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
