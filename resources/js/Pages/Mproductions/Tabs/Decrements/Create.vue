<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue';
import { useQuasar } from 'quasar'

const props = defineProps({
    products: Array,
    mproduction_id: String,
})

const form = useForm({
    mproduction_id: props.mproduction_id,
    product: null,
    quantity: 1,
    weight: 0.00,
    price: 0.00,
    status: 0,
})

const productsOptions = props.products?.map(product => ({
    label: product.nomenklature + ' - ' + product.name,
    value: product.id,
    price: product.price,
    me: product.me,
    stock: product.stock,
}))

const $q = useQuasar()
const mdecrementsStore = () => {
    form.post(route('mdecrements.store'), {
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

const me = ref('')
const total = ref(0)

watch(
    () => form.product,
    (newValue, oldValue) => {
        if (newValue && newValue.value !== oldValue?.value) {
            form.price = newValue.price
        }
        me.value = newValue.me
        total.value = newValue.stock
    }
)

const title = `Добавяне на разход към Процес №${props.mproduction_id}`
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
                                    class="q-gutter-xl"
                                    autofocus
                                >
                                    <q-select
                                        v-model="form.product"
                                        :options="productsOptions"
                                        class="col"
                                        label="Избери продукт *"
                                        hint="Избери продукт който да разходваш."
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.product"
                                    />
                                    <div class="row">
                                        <div class="col-9 q-mr-md">
                                            <q-input
                                                v-model.number="form.quantity"
                                                class="col"
                                                type="number"
                                                label="Количество"
                                                hint="Количество от избрания продукт за разходване."
                                                :error="form.hasErrors"
                                                :error-message="form.errors.quantity"
                                            >
                                                <template v-slot:append>
                                                    <span class="text-subtitle1">{{ me }}</span>
                                                </template>
                                            </q-input>
                                        </div>
                                        <div class="col">
                                            <q-input
                                                v-model.number="total"
                                                type="number"
                                                label="Наличност"
                                                readonly
                                                hint="Общо налично количество от продукта в склада"
                                            >
                                                <template v-slot:append>
                                                    <span class="text-subtitle1">{{ me }}</span>
                                                </template>
                                            </q-input>
                                        </div>
                                    </div>
                                    <q-input
                                        v-model.number="form.price"
                                        class="col"
                                        type="number"
                                        label="Цена *"
                                        hint="Единична цена на избрания продукт за разходване."
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
                        @click.prevent="router.get(route('mproductions.show', mproduction_id))"
                        color="primary"
                        flat
                        :label="`Процес №${mproduction_id}`"
                        icon="mdi-menu-left"
                    />

                    <q-btn
                        @click.prevent="mdecrementsStore"
                        label="Добави разхода"
                        color="primary"
                        icon="mdi-plus"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
