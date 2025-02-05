<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed, onMounted, ref } from 'vue'

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
    weight: 0.00,
    price: props.uproduction.price,
    status: 0,
    type: props.type,
})

const $q = useQuasar()
const uincrementsStore = () => {
    form.post(route('uincrements.store'), {
        onFinish: () => {
            form.reset('quantity', 'weight')
        }
    })
}

const me = ref('')
const total = ref(0)

onMounted(() => {
    me.value = props.uproduction.product?.me
    total.value = props.uproduction.stock
})

const typeTitle = computed(() => {
    switch (props.type) {
        case 'Продажба':
            return {
                'title': 'Добавяне на приход от продажба на прасета',
                'button': 'Приключи продажбата',
            }
        case 'Ремонт':
            return {
                'title': 'Добавяне на приход от ремонтни прасета',
                'button': 'Приключи продажбата',
            }
        default:
            return {
                'title': 'Добавяне на приход',
                'button': 'Добави прихода',
            }
    }
})
const title = `${typeTitle.value.title} към Процес №${props.uproduction.id}`
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
                                        :model-value="`[${product.nomenklature}] ${product.name}`"
                                        class="col"
                                        label="Продукт"
                                        hint="Продукт избран в прихода"
                                        readonly
                                    />
                                    <div class="row">
                                        <div class="col-9 q-mr-md">
                                            <q-input
                                                v-model.number="form.quantity"
                                                class="col"
                                                type="number"
                                                label="Количество"
                                                hint="Количество от избрания продукт за приход."
                                                autofocus
                                                :error="form.hasErrors"
                                                :error-message="form.errors.quantity"
                                            >
                                                <template v-slot:append>
                                                    <span class="text-subtitle1">{{ product.me }}</span>
                                                </template>
                                            </q-input>
                                        </div>
                                        <div class="col">
                                            <q-input
                                                v-model.number="total"
                                                type="number"
                                                label="Наличност"
                                                readonly
                                                hint="Общо налично количество прасета в процеса"
                                            >
                                                <template v-slot:append>
                                                    <span class="text-subtitle1">{{ me }}</span>
                                                </template>
                                            </q-input>
                                        </div>
                                    </div>
                                    <q-input
                                        v-model.number="form.weight"
                                        type="number"
                                        label="Тегло"
                                        :hint="`Общо тегло на ${type === 'Продажба' ? 'продаваните' : type === 'Ремонт' ? 'ремонтните' : ''} прасета`"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.weight"
                                    >
                                        <template v-slot:append>
                                            <span class="text-subtitle1">кг</span>
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
                        @click.prevent="uincrementsStore"
                        :label="typeTitle.button"
                        color="primary"
                        icon="mdi-plus"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
