<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'

const props = defineProps({
    uproductions: {
        type: Object,
    },
    filter: {
        type: String,
    },
    uhall: {
        type: String,
    },
    uhall_name: {
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
        name: 'uhall_id',
        align: 'left',
        label: 'Хале',
        field: row => row.uhall.name,
        sortable: true
    },
    {
        name: 'status',
        align: 'left',
        label: 'Състояние',
        field: row => row.status === 1 ? 'Активен' : 'Приключен',
        sortable: true
    },
    {
        name: 'created_at',
        align: 'left',
        label: 'Стариран на',
        field: row => moment(row.created_at).format('DD.MM.YY HH:mm'),
        sortable: true
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const title = 'Продукционни процеси Угояване'
const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = {
    page: props.uproductions.meta.current_page,
    rowsPerPage: props.uproductions.meta.per_page,
    rowsNumber: props.uproductions.meta.total,
    sortBy: 'id',
    descending: false,
}
const filter = ref(props.filter)
const navigationActive = ref(false)

const onRequest = (requestProp) => {
    router.get(
        route('uproductions.index'),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: requestProp.pagination.sortBy || 'id',
            sortOrder: requestProp.pagination.descending ? 'desc' : 'asc',
            filter: filter.value,
            uhall: props.uhall,
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
                            :title="uhall_name ? `Процеси Угояване в Хале: ${uhall_name}`
                                :
                                'Всички Процеси Угояване'"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="uproductions.data"
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
                            <template v-slot:body-cell-actions="props">
                                <q-td align="center">
                                    <q-btn
                                        v-if="hasPermission('view')"
                                        title="Управлявай процеса"
                                        icon="mdi-file-tree"
                                        color="primary"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('uproductions.show', props.row.id))"
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
                                </q-td>
                            </template>
                        </q-table>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Табло"
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
