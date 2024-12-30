<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    cities: {
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
        style: 'width: 80px;',
        sortable: true,
    },
    {
        name: 'name',
        align: 'left',
        label: 'Име',
        field: 'name',
        style: 'width: 240px;',
        sortable: true,
    },
    {
        name: 'factories',
        align: 'left',
        label: 'Бази',
        sortable: false,
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
        sortable: false,
    }
]

const fieldFactories = (row) => { return row.factories.map(obj => obj.name).join(', ') }

const title = 'Населени места'
const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = {
    page: props.cities.meta.current_page,
    rowsPerPage: props.cities.meta.per_page,
    rowsNumber: props.cities.meta.total
}
const filter = ref(props.filter)

const onRequest = (requestProp) => {
    router.get(
        route('cities.index'),
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

const confirm = (city_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш населеното място?',
        cancel: true,
        persistent: true,
        ok: {
            label: 'Да',
            color: 'primary',

        },
        cancel: {
            label: 'Откажи',
            color: 'grey-1',
            textColor: 'grey-10',
            flat: true
        },
    }).onOk(() => {
        router.delete(route('cities.destroy', city_id), {
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
    }).onCancel(() => { }).onDismiss(() => { })
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
                            ref="tableRef"
                            class="my-sticky-header-table"
                            bordered
                            title="Населени места"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="cities.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="onRequest"
                        >
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
                            <template v-slot:body-cell-actions="props">
                                <q-td
                                    align="center"
                                    style="width: 120px;"
                                >
                                    <q-btn
                                        v-if="hasPermission('update')"
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        title="Промяна на населеното място"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('cities.edit', props.row.id))"
                                    />
                                    <q-btn
                                        v-if="hasPermission('delete')"
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        title="Изтриване на населеното място"
                                        dense
                                        flat
                                        rounded
                                        @click="confirm(props.row.id)"
                                    />
                                </q-td>
                            </template>
                            <template v-slot:body-cell-factories="props">
                                <q-td
                                    :props="props"
                                    class="text-wrap"
                                >
                                    {{ fieldFactories(props.row) }}
                                </q-td>
                            </template>
                        </q-table>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        flat
                        label="Табло"
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />

                    <q-btn
                        v-if="hasPermission('create')"
                        color="primary"
                        label="Ново населено място"
                        icon="mdi-file-document-plus-outline"
                        @click="router.get(route('cities.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
