<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const props = defineProps({
    mincrement: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    mproduction_id: props.mincrement?.mproduction?.id,
    product: props.mincrement?.product,
    quantity: props.mincrement?.quantity,
    price: props.mincrement?.price,
    status: props.mincrement?.status,
    type: props.mincrement?.type,
})

const $q = useQuasar()
const mincrementsUpdate = () => {
    form.put(route('mincrements.update', props.mincrement.id), {
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

const title = `Добавяне на приход към Процес №${props.mincrement?.mproduction?.id}`
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
                                        :model-value="`[${mincrement.product?.nomenklature}] ${mincrement.product?.name}`"
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
                                        autofocus
                                        numeric-keyboard-toggle
                                    >
                                        <template v-slot:append>
                                            <span class="text-subtitle1">{{ mincrement.product.me }}</span>
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
                        @click.prevent="router.get(route('mproductions.show', mincrement.mproduction?.id))"
                        color="primary"
                        flat
                        :label="`Продукционен процес №${mincrement.mproduction?.id}`"
                        icon="mdi-menu-left"
                    />

                    <q-btn
                        @click.prevent="mincrementsUpdate"
                        label="Запиши промените"
                        color="primary"
                        icon="mdi-content-save-outline"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
