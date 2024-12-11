<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    mproductions: {
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
        field: row => row.id,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'mhall',
        align: 'left',
        label: 'Хале',
        field: row => row.mhall.name,
        sortable: true
    },
    { name: 'name', align: 'left', label: 'Име', field: 'name', sortable: true },
    { name: 'status', align: 'left', label: 'Състояние', field: 'status', sortable: true },
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
    rowsNumber: props.mproductions.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)

const onRequest = (requestProp) => {
    router.get(
        route('mproductions.index'),
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

const activateNavigation = () => {
    navigationActive.value = true
}

const deactivateNavigation = () => {
    navigationActive.value = false
}

const tableClass = computed(() => navigationActive.value === true ? 'shadow-8 no-outline' : null)
</script>

<template>

    <Head title="Продукционни процеси Майки"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Табло"
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Майки</h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <q-table
                ref="tableRef"
                class="my-sticky-header-table"
                :class="tableClass"
                bordered
                title="МАЙКИ - Продукционни процеси"
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
                <template v-slot:body-cell-actions="props">
                    <q-td align="center">
                        <q-btn
                            v-if="hasPermission('update')"
                            icon="mdi-pencil-outline"
                            color="primary"
                            dense
                            flat
                            rounded
                            @click="router.get(route('mproductions.edit', props.row.id))"
                        />
                        <q-btn
                            v-if="hasPermission('delete')"
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
        </q-page>
    </DefaultLayout>
</template>
