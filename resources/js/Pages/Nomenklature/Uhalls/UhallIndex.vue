<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { useQuasar } from "quasar";
import { usePermission } from "@/composables/permissions";

const props = defineProps({
    uhalls: {
        type: Object,
    },
    filter: {
        type: String,
    },
    sortBy: {
        type: String,
    },
    sortOrder: {
        type: String,
    },
});

const columns = [
    {
        name: "id",
        required: true,
        label: "№",
        align: "left",
        field: "id",
        style: "width: 80px;",
        sortable: false,
    },
    {
        name: "factory_id",
        align: "left",
        label: "База",
        field: (row) => row.factory.name,
        style: "width: 240px;",
        sortable: false,
    },
    {
        name: "silo_id",
        align: "left",
        label: "Силоз",
        field: (row) => row.silo.name,
        style: "width: 240px;",
        sortable: false,
    },
    {
        name: "name",
        align: "left",
        label: "Име",
        field: "name",
        sortable: false,
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
        sortable: false,
    },
];

const isSortColumn = (col) => {
    return ["id", "factory_id", "silo_id", "name"].includes(col);
};

const title = "Халета за угояване";
const { hasPermission } = usePermission();
const $q = useQuasar();
const pagination = ref({
    page: props.uhalls.meta.current_page,
    rowsPerPage: props.uhalls.meta.per_page,
    rowsNumber: props.uhalls.meta.total,
    sortBy: props.sortBy,
    sortOrder: props.sortOrder,
});
const filter = ref(props.filter);

const uhallsIndex = (requestProp) => {
    router.get(
        route("uhalls.index"),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: pagination.value.sortBy,
            sortOrder: pagination.value.sortOrder,
            filter: filter.value,
        },
        {
            preserveState: false,
            onError: (errors) => {
                Object.values(errors)
                    .flat()
                    .forEach((error) => {
                        $q.notify({
                            message: error,
                            icon: "mdi-alert-circle-outline",
                            type: "negative",
                        });
                    });
            },
        }
    );
};

const sortByColumn = (col) => {
    if (pagination.value.sortBy === col) {
        pagination.value.sortOrder =
            pagination.value.sortOrder === "asc" ? "desc" : "asc";
    } else {
        pagination.value.sortBy = col;
        pagination.value.sortOrder = "asc";
    }
    uhallsIndex({ pagination });
};

const confirm = (uhall_id) => {
    $q.dialog({
        title: "Потвърди",
        message: "Желаеш ли да изтриеш халето за угояване?",
        cancel: true,
        persistent: true,
        ok: {
            label: "Да",
            color: "primary",
        },
        cancel: {
            label: "Откажи",
            color: "grey-1",
            textColor: "grey-10",
            flat: true,
        },
    })
        .onOk(() => {
            router.delete(route("uhalls.destroy", uhall_id), {
                onError: (errors) => {
                    Object.values(errors)
                        .flat()
                        .forEach((error) => {
                            $q.notify({
                                message: error,
                                icon: "mdi-alert-circle-outline",
                                type: "negative",
                            });
                        });
                },
            });
        })
        .onCancel(() => {})
        .onDismiss(() => {});
};
</script>

<template>
    <Head :title="title"></Head>

    <DefaultLayout :title="title" icon="mdi-file-document-outline">
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <q-table
                            class="my-sticky-header-table"
                            bordered
                            title="Халета за угояване"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="uhalls.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="uhallsIndex"
                        >
                            <template v-slot:header-cell="props">
                                <q-th
                                    :props="props"
                                    :class="
                                        isSortColumn(props.col.name)
                                            ? 'cursor-pointer text-center'
                                            : 'text-center'
                                    "
                                    @click="
                                        isSortColumn(props.col.name)
                                            ? sortByColumn(props.col.name)
                                            : null
                                    "
                                >
                                    {{ props.col.label }}&nbsp;
                                    <q-icon
                                        v-if="isSortColumn(props.col.name)"
                                        :name="
                                            props.col.name ===
                                                pagination.sortBy &&
                                            pagination.sortOrder === 'desc'
                                                ? 'mdi-sort-ascending'
                                                : 'mdi-sort-descending'
                                        "
                                    />
                                </q-th>
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
                            <template v-slot:body-cell-actions="props">
                                <q-td align="center" style="width: 120px">
                                    <q-btn
                                        v-if="hasPermission('update')"
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        title="Промяна на хале за угояване"
                                        dense
                                        flat
                                        rounded
                                        @click="
                                            router.get(
                                                route(
                                                    'uhalls.edit',
                                                    props.row.id
                                                )
                                            )
                                        "
                                    />
                                    <q-btn
                                        v-if="hasPermission('delete')"
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        title="Изтриване на хале за угояване"
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
                        @click="router.get(route('uhalls.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
