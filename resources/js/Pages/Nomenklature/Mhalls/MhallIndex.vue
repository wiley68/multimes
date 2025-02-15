<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    mhalls: {
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
        style: 'width: 60px;',
        sortable: true,
    },
    {
        name: 'factory_id',
        align: 'left',
        label: 'База',
        field: row => row.factory.name,
        style: 'width: 140px;',
        sortable: true,
    },
    {
        name: 'silo_id',
        align: 'left',
        label: 'Силоз',
        field: row => row.silo.name,
        style: 'width: 140px;',
        sortable: true,
    },
    {
        name: 'type',
        align: 'left',
        label: 'Тип',
        field: row => row.type,
        style: 'width: 100px;',
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
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const title = 'Халета за майки'
const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = {
    page: props.mhalls.meta.current_page,
    rowsPerPage: props.mhalls.meta.per_page,
    rowsNumber: props.mhalls.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)

const onRequest = (requestProp) => {
    router.get(
        route('mhalls.index'),
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

const confirm = (mhall_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш халето за майки?',
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
        router.delete(route('mhalls.destroy', mhall_id), {
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
                            class="my-sticky-header-table"
                            :class="tableClass"
                            bordered
                            title="Халета за майки"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="mhalls.data"
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
                                <q-td
                                    align="center"
                                    style="width: 80px;"
                                >
                                    <q-btn
                                        v-if="hasPermission('update')"
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        title="Промяна на хале за майки"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('mhalls.edit', props.row.id))"
                                    />
                                    <q-btn
                                        v-if="hasPermission('delete')"
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        title="Изтриване на хале за майки"
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
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />

                    <q-btn
                        v-if="hasPermission('create')"
                        color="primary"
                        label="Ново хале"
                        icon="mdi-file-document-plus-outline"
                        @click="router.get(route('mhalls.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
