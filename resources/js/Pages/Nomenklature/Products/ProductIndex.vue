<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { useQuasar } from "quasar";
import { usePermission } from "@/composables/permissions";

const props = defineProps({
    products: {
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
        style: "width: 60px;",
        sortable: false,
    },
    {
        name: "nomenklature",
        align: "left",
        label: "Номенклатура",
        field: "nomenklature",
        style: "width: 80px;",
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
        name: "type",
        align: "left",
        label: "Предназначение",
        field: "type",
        style: "width: 140px;",
        sortable: false,
    },
    {
        name: "stock",
        align: "left",
        label: "Наличност",
        field: "stock",
        style: "width: 120px;",
        sortable: false,
    },
    {
        name: "me",
        align: "left",
        label: "м.е.",
        field: "me",
        style: "width: 80px;",
        sortable: false,
    },
    {
        name: "price",
        align: "left",
        label: "Цена",
        field: "price",
        style: "width: 120px;",
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
    return [
        "id",
        "nomenklature",
        "name",
        "type",
        "stock",
        "me",
        "price",
    ].includes(col);
};

const title = "Продукти";
const { hasPermission } = usePermission();
const $q = useQuasar();
const pagination = ref({
    page: props.products.meta.current_page,
    rowsPerPage: props.products.meta.per_page,
    rowsNumber: props.products.meta.total,
    sortBy: props.sortBy,
    sortOrder: props.sortOrder,
});
const filter = ref(props.filter);

const productsIndex = (requestProp) => {
    router.get(
        route("products.index"),
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
    productsIndex({ pagination });
};

const confirm = (product_id) => {
    $q.dialog({
        title: "Потвърди",
        message: "Желаеш ли да изтриеш продукта?",
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
            router.delete(route("products.destroy", product_id), {
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
                            :title="title"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="products.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="productsIndex"
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
                                        title="Промяна на продукт"
                                        dense
                                        flat
                                        rounded
                                        @click="
                                            router.get(
                                                route(
                                                    'products.edit',
                                                    props.row.id
                                                )
                                            )
                                        "
                                    />
                                    <q-btn
                                        v-if="hasPermission('delete')"
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        title="Изтриване на продукт"
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
                        label="Нов продукт"
                        icon="mdi-file-document-plus-outline"
                        @click="router.get(route('products.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
