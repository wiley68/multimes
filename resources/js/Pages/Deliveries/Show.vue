<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { computed } from 'vue';

const props = defineProps({
    delivery: {
        type: Object,
        required: true,
    }
})

const form = useForm({
    document: props.delivery?.document,
    supplier: props.delivery?.supplier,
    status: props.delivery?.status === 0 ? { label: 'Типов документ', value: 0 } : { label: 'Приключен документ', value: 1 },
})

const subdeliveryColumns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: 'id',
        sortable: true,
        style: "width: 40px;",
    },
    {
        name: 'product',
        align: 'left',
        label: 'Продукт',
        field: row => row.product.name,
        sortable: true
    },
    {
        name: 'quantity',
        align: 'left',
        label: 'Количество',
        field: 'quantity',
        sortable: true,
        style: "width: 80px;",
    },
    {
        name: 'me',
        align: 'left',
        label: 'м.е.',
        field: row => row.product.me,
        sortable: false,
        style: "width: 80px;",
    },
    {
        name: 'price',
        align: 'left',
        label: 'Цена',
        field: 'price',
        sortable: true,
        style: "width: 100px;",
    },
    {
        name: 'allprice',
        align: 'left',
        label: 'Общо',
        field: row => parseFloat(row.price * row.quantity).toFixed(2),
        sortable: true,
        style: "width: 120px;",
    }
]

const totalPrice = computed(() => {
    return props.delivery.subdeliveries
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
});

const title = `Доставка №${props.delivery.id}`
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
                        <div class="column q-gutter-y-md flex-grow flex-center">
                            <q-card class="q-pa-md full-width">
                                <q-form class="row q-gutter-xl">
                                    <q-input
                                        :model-value="delivery.id"
                                        class="col"
                                        label="Доставка №"
                                        hint="Пореден номер на доставката"
                                        readonly
                                    />

                                    <q-input
                                        v-model="form.document"
                                        class="col"
                                        label="Документ номер"
                                        hint="Номер на насрещния документ за доставка"
                                        readonly
                                    />

                                    <q-input
                                        v-model="form.supplier"
                                        class="col"
                                        label="Доставчик"
                                        hint="Доставчик на продуктите"
                                        readonly
                                    />
                                </q-form>
                            </q-card>

                            <q-table
                                class="my-sticky-header-table full-width"
                                bordered
                                rows-per-page-label="Записи на страница"
                                separator="cell"
                                no-data-label="Липсват данни"
                                no-results-label="Няма съответстващи записи"
                                loading-label="Данните се зареждат..."
                                table-header-class="bg-grey-3"
                                :rows="delivery.subdeliveries"
                                :columns="subdeliveryColumns"
                                row-key="id"
                                :rows-per-page-options=[7]
                            >
                                <template v-slot:bottom-row>
                                    <q-tr>
                                        <q-td
                                            colspan="5"
                                            class="text-weight-bold"
                                        >Общо:</q-td>
                                        <q-td class="text-weight-bold">
                                            {{ totalPrice }}
                                        </q-td>
                                    </q-tr>
                                </template>
                            </q-table>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Доставки"
                        title="Връща към доставки."
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('deliveries.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>