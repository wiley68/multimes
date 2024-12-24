<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'

const props = defineProps({
    mproductions: {
        type: Object,
    },
    filter: {
        type: String,
    },
    mhall: {
        type: String,
    },
    mhall_name: {
        type: String,
    },
})

const columns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: row => row.id,
        format: val => `${val}`,
        sortable: true,
        sortMethod: (a, b) => (a < b ? -1 : a > b ? 1 : 0),
    },
    {
        name: 'mhall_id',
        align: 'left',
        label: 'Хале',
        field: 'mhall_id',
        sortable: true
    },
    {
        name: 'status',
        align: 'left',
        label: 'Състояние',
        field: 'status',
        sortable: true
    },
    {
        name: 'created_at',
        align: 'left',
        label: 'Стариран на',
        field: 'created_at',
        sortable: true
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = {
    page: props.mproductions.meta.current_page,
    rowsPerPage: props.mproductions.meta.per_page,
    rowsNumber: props.mproductions.meta.total,
    sortBy: 'id',
    descending: false,
}
const title = 'Процеси Майки'
const filter = ref(props.filter)
const navigationActive = ref(false)

const onRequest = (requestProp) => {
    router.get(
        route('mproductions.index'),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: requestProp.pagination.sortBy || 'id',
            sortOrder: requestProp.pagination.descending ? 'desc' : 'asc',
            filter: filter.value,
            mhall: props.mhall,
        },
        {
            preserveState: true,
        }
    );
}

const activateNavigation = () => {
    navigationActive.value = true
}

const deactivateNavigation = () => {
    navigationActive.value = false
}

const tableClass = computed(() => navigationActive.value === true ? 'shadow-8 no-outline' : null)
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
                            :class="tableClass"
                            bordered
                            :title="mhall_name ? `Процеси Майки в Хале: ${mhall_name}`
                                :
                                title"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="mproductions.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="onRequest"
                            @focusin="activateNavigation"
                            @focusout="deactivateNavigation"
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
                            <template v-slot:body="props">
                                <q-tr
                                    :props="props"
                                    :class="props.row.status === 1 ? 'bg-red-3' : ''"
                                >
                                    <q-td
                                        v-for="col in props.cols"
                                        :key="col.name"
                                        :props="props"
                                    >
                                        <div v-if="col.name === 'actions'">
                                            <q-btn
                                                v-if="hasPermission('view')"
                                                title="Управлявай процеса"
                                                icon="mdi-file-tree"
                                                color="primary"
                                                dense
                                                flat
                                                rounded
                                                @click="router.get(route('mproductions.show', props.row.id))"
                                            />
                                            <q-btn
                                                v-if="hasPermission('delete') && props.row.status === 0"
                                                title="Изтрий процеса"
                                                icon="mdi-delete-outline"
                                                color="negative"
                                                dense
                                                flat
                                                rounded
                                                @click="confirm(props.row.id)"
                                            />
                                        </div>
                                        <div v-else-if="col.name === 'status'">
                                            {{ props.row['status'] === 1 ? 'Активен' : 'Приключен' }}
                                        </div>
                                        <div v-else-if="col.name === 'mhall_id'">
                                            {{ props.row.mhall.name }}
                                        </div>
                                        <div v-else-if="col.name === 'created_at'">
                                            {{ moment(props.row['created_at']).format('DD.MM.YY HH:mm') }}
                                        </div>
                                        <div v-else>
                                            {{ props.row[col.name] }}
                                        </div>
                                    </q-td>
                                </q-tr>
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
