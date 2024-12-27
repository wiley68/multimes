<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    stores: {
        type: Object,
        required: true,
    }
})

const columns = [
    {
        name: 'nomenklature',
        align: 'left',
        label: 'Номенклатура',
        field: 'nomenklature',
        style: "width: 100px;",
    },
    {
        name: 'name',
        align: 'left',
        label: 'Продукт',
        field: 'name',
    },
    {
        name: 'object',
        align: 'left',
        label: 'Обект',
        field: 'object',
    },
    {
        name: 'quantity',
        align: 'left',
        label: 'Количество',
        field: 'quantity',
        style: "width: 80px;",
    },
    {
        name: 'me',
        align: 'left',
        label: 'м.е.',
        field: 'me',
        style: "width: 80px;",
    },
]

const totalPrice = computed(() => {
    return props.stores
        .reduce((total, item) => total + item.quantity, 0)
        .toFixed(2);
})

const title = `Наличности за продукт: ${props.stores[0]?.name}`
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
                            <q-table
                                class="my-sticky-header-table full-width"
                                bordered
                                rows-per-page-label="Записи на страница"
                                separator="cell"
                                no-data-label="Липсват данни"
                                no-results-label="Няма съответстващи записи"
                                loading-label="Данните се зареждат..."
                                table-header-class="bg-grey-3"
                                :rows="stores"
                                :columns="columns"
                                row-key="id"
                                :rows-per-page-options=[7]
                            >
                                <template v-slot:bottom-row>
                                    <q-tr>
                                        <q-td
                                            colspan="3"
                                            class="text-weight-bold"
                                        >Общо:</q-td>
                                        <q-td class="text-weight-bold text-accent">
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
                        label="Наличности"
                        title="Връща към наличности."
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('stores.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>