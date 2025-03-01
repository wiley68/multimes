<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
    silo: {
        type: Object,
        required: true
    },
    products: Array,
    mproduction: Number,
})

const form = useForm({
    name: props.silo?.name,
    factory: props.silo?.factory,
    maxqt: props.silo?.maxqt,
    stock: props.silo?.maxqt,
    price: props.silo?.price,
    product: props.silo.product === null ? props.products.length > 0 ? props.products[0] : null : props.silo?.product,
})

const $q = useQuasar()
const total = ref(0)
const me = ref('')

const silosMload = () => {
    form.put(route('silos.mload', { silo: props.silo.id, mproduction: props.mproduction }), {
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

onMounted(() => {
    me.value = form.product ? form.product.me : ''
    total.value = form.product ? form.product.stock : 0
})

watch(
    () => form.product,
    (newValue, oldValue) => {
        me.value = newValue.me
        total.value = newValue.stock
    }
)

const title = `${props.silo.name} - Зареждане`
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
                                        :option-label="element => `[${element.nomenklature}] ${element.name} - [${element.type}]`"
                                        label="Избери продукт"
                                        hint="Избери продукт който ще се добави в силоза."
                                        :error="form.hasErrors"
                                        :error-message="form.errors.product"
                                    />
                                    <div class="row">
                                        <div class="col-9 q-mr-md">
                                            <q-input
                                                v-model.number="form.stock"
                                                type="number"
                                                label="Количество"
                                                hint="Количество което ще се добави в силоза"
                                                :error="form.hasErrors"
                                                :error-message="form.errors.stock"
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
                                                hint="Общо налично количество фураж в склада"
                                            >
                                                <template v-slot:append>
                                                    <span class="text-subtitle1">{{ me }}</span>
                                                </template>
                                            </q-input>
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
                        :label="`Процес №${mproduction}`"
                        flat
                        icon="mdi-menu-left"
                        @click="mproductionShow"
                    />
                    <q-btn
                        @click.prevent="silosMload"
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
