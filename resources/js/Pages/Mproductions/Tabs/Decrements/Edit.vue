<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue';
import { useQuasar } from 'quasar'

const props = defineProps({
    mdecrement: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    mproduction_id: props.mdecrement?.mproduction?.id,
    product: { value: props.mdecrement?.product.id, label: props.mdecrement?.product.name },
    quantity: props.mdecrement?.quantity,
    weight: 0.00,
    price: props.mdecrement?.price,
    status: props.mdecrement?.status,
})

const $q = useQuasar()
const mdecrementsUpdate = () => {
    form.put(route('mdecrements.update', props.mdecrement.id), {
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

onMounted(() => {
    me.value = props.mdecrement.product?.me
    total.value = props.mdecrement.product?.stock
})

const title = `Добавяне на разход към Процес №${props.mdecrement.mproduction?.id}`
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
                                    <q-input
                                        :model-value="mdecrement.product ? `[${mdecrement.product.nomenklature}] ${mdecrement.product.name}` : ''"
                                        class="col"
                                        label="Продукт"
                                        hint="Продукт избран в разхода"
                                        readonly
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
                                                autofocus
                                                numeric-keyboard-toggle
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
                        @click.prevent="router.get(route('mproductions.show', mdecrement.mproduction?.id))"
                        color="primary"
                        flat
                        :label="`Процес №${mdecrement.mproduction?.id}`"
                        icon="mdi-menu-left"
                    />

                    <q-btn
                        @click.prevent="mdecrementsUpdate"
                        label="Запиши промените"
                        color="primary"
                        icon="mdi-content-save-outline"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
