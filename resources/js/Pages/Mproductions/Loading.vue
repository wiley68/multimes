<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    },
    products: Array,
})

const form = useForm({
    stock: 0,
    price: props.mproduction?.price,
    product: props.mproduction?.product,
})

const $q = useQuasar()

const mproductionLoad = () => {
    form.put(route('mproductions.load', props.mproduction), {
        onFinish: () => {
            form.reset('stock')
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

const mproductionShow = () => {
    router.get(
        route('mproductions.show', props.mproduction),
        {
            onError: errors => {
                Object.values(errors).flat().forEach((error) => {
                    $q.notify({
                        message: error,
                        icon: 'mdi-alert-circle-outline',
                        type: 'negative',
                    });
                });
            },
        }
    )
}

const title = `Хале: ${props.mproduction.mhall.name}, Процес: №${props.mproduction.id} - Зареждане`
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-upload-multiple-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <div class="column flex-grow flex-center">
                            <q-card class="q-pa-md full-width">
                                <q-form class="q-gutter-md">
                                    <q-select
                                        v-model="form.product"
                                        :options="products"
                                        :option-label="option => `[${option.nomenklature}] ${option.name} - [${option.type}]`"
                                        label="Избери прасета"
                                        hint="Избери прасета които ще се добавят към процеса."
                                        :error="form.hasErrors"
                                        :error-message="form.errors.product"
                                    />
                                    <q-input
                                        v-model.number="form.stock"
                                        type="number"
                                        label="Количество [бр]"
                                        hint="Брой прасета които ще се добавят към процеса [бр]"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.stock"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        :label="`Процес №${mproduction.id}`"
                        flat
                        icon="mdi-menu-left"
                        @click="mproductionShow"
                    />
                    <q-btn
                        @click.prevent="mproductionLoad"
                        label="Зареди"
                        type="submit"
                        icon="mdi-upload-multiple-outline"
                        color="primary"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
