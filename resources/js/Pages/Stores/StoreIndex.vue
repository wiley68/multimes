<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'

const props = defineProps({
    stores: {
        type: Object,
    },
    filter: {
        type: String,
    },
})

const columns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: 'id',
        style: 'width: 40px;',
        sortable: true,
    },
    {
        name: 'nomenklature',
        align: 'left',
        label: 'Номенклатура',
        field: 'nomenklature',
        style: 'width: 80px;',
        sortable: true,
    },
    {
        name: 'name',
        align: 'left',
        label: 'Име',
        field: 'name',
        sortable: true,
    },
    {
        name: 'stock',
        align: 'left',
        label: 'Наличност склад',
        field: 'stock',
        style: 'width: 100px;',
        sortable: false,
    },
    {
        name: 'stock_out',
        align: 'left',
        label: 'Наличност обекти',
        field: 'stock_out',
        style: 'width: 100px;',
        sortable: false,
    },
    {
        name: 'total',
        align: 'left',
        label: 'Общо',
        field: 'total',
        style: 'width: 100px; color: #9C27B0; font-weight: bold; font-size: 110%;',
        sortable: false,
    },
    {
        name: 'me',
        align: 'left',
        label: 'м.е.',
        field: 'me',
        style: 'width: 80px;',
        sortable: false,
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
        style: 'width: 80px;',
    },
]

const title = 'Наличности'
const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = {
    page: props.stores.current_page,
    rowsPerPage: props.stores.per_page,
    rowsNumber: props.stores.total
}
const filter = ref(props.filter)

const onRequest = (requestProp) => {
    router.get(
        route('products.index'),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: requestProp.pagination.sortBy,
            sortOrder: requestProp.pagination.descending ? 'desc' : 'asc',
            filter: filter.value,
        },
        {
            preserveState: false,
        }
    );
}
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <q-table
                            class="my-sticky-header-table"
                            bordered
                            :title="title"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="stores.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="onRequest"
                        >
                            <template v-slot:body="props">
                                <q-tr :props="props">
                                    <q-td
                                        v-for="col in props.cols"
                                        :key="col.name"
                                        :props="props"
                                    >
                                        <div v-if="col.name === 'actions'">
                                            <q-btn
                                                v-if="hasPermission('view')"
                                                icon="mdi-table-eye"
                                                color="primary"
                                                title="Преглед на наличността"
                                                dense
                                                flat
                                                rounded
                                                @click="router.get(route('deliveries.show', props.row.id))"
                                            />
                                        </div>
                                        <div v-else-if="col.name === 'stock'">
                                            {{ props.row.stock.toFixed(2) }}
                                        </div>
                                        <div v-else-if="col.name === 'stock_out'">
                                            {{ props.row.silos.reduce((sum, item) => {
                                                return sum + parseFloat(item.stock)
                                            }, 0).toFixed(2) }}
                                        </div>
                                        <div v-else-if="col.name === 'total'">
                                            {{ (props.row.stock + props.row.silos.reduce((sum, item) => {
                                                return sum + parseFloat(item.stock)
                                            }, 0)).toFixed(2) }}
                                        </div>
                                        <div v-else>
                                            {{ props.row[col.name] }}
                                        </div>
                                    </q-td>
                                </q-tr>
                            </template>
                            <template v-slot:top-right>
                                <q-input
                                    v-model="filter"
                                    borderless
                                    dense
                                    autofocus
                                    debounce="600"
                                    placeholder="Търси..."
                                >
                                    <template v-slot:append>
                                        <q-icon name="mdi-magnify" />
                                    </template>
                                </q-input>
                            </template>
                        </q-table>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Табло"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
