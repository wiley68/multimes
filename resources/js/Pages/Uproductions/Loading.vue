<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
    products: Array,
})

const form = useForm({
    stock: 0,
    price: props.uproduction?.price,
    product: props.uproduction.product === null ? props.products.length > 0 ? props.products[0] : null : props.uproduction?.product,
})

const $q = useQuasar()

const uproductionLoad = () => {
    form.put(route('uproductions.load', props.uproduction), {
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

const uproductionShow = () => {
    router.get(
        route('uproductions.show', props.uproduction),
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

const title = `Хале: ${props.uproduction.uhall.name}, Процес: №${props.uproduction.id} - Зареждане`
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
                                    <div class="row">
                                        <div class="col-9">
                                            <q-input
                                                v-model.number="form.stock"
                                                type="number"
                                                label="Количество [бр]"
                                                hint="Брой прасета които ще се добавят към процеса [бр]"
                                                :error="form.hasErrors"
                                                :error-message="form.errors.stock"
                                            />
                                        </div>
                                        <div class="col">
                                            <q-input
                                                v-model.number="form.stock"
                                                type="number"
                                                label="Количество [бр]"
                                                hint="Брой прасета които ще се добавят към процеса [бр]"
                                                :error="form.hasErrors"
                                                :error-message="form.errors.stock"
                                            />
                                        </div>
                                    </div>
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        :label="`Процес №${uproduction.id}`"
                        flat
                        icon="mdi-menu-left"
                        @click="uproductionShow"
                    />
                    <q-btn
                        @click.prevent="uproductionLoad"
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
