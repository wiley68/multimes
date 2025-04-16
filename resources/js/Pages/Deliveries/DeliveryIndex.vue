<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { useQuasar } from "quasar";
import { usePermission } from "@/composables/permissions";
import moment from "moment";

const props = defineProps({
    deliveries: {
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
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: false,
    },
    {
        name: "document",
        align: "left",
        label: "Документ номер",
        field: "document",
        sortable: false,
    },
    {
        name: "supplier",
        align: "left",
        label: "Доставчик",
        field: "supplier",
        sortable: false,
    },
    {
        name: "created_at",
        align: "left",
        label: "Създаден на",
        field: "created_at",
        sortable: false,
    },
    {
        name: "status",
        align: "left",
        label: "Тип",
        field: "status",
        sortable: false,
    },
    {
        name: "total",
        align: "left",
        label: "Общо",
        field: "total",
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
    return ["id", "document", "supplier", "created_at", "status"].includes(col);
};

const title = "Доставки";
const { hasPermission } = usePermission();
const $q = useQuasar();
const pagination = ref({
    page: props.deliveries.meta.current_page,
    rowsPerPage: props.deliveries.meta.per_page,
    rowsNumber: props.deliveries.meta.total,
    sortBy: props.sortBy,
    sortOrder: props.sortOrder,
});
const filter = ref(props.filter);

const deliveriesIndex = (requestProp) => {
    router.get(
        route("deliveries.index"),
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
    deliveriesIndex({ pagination });
};

const confirm = (delivery_id) => {
    $q.dialog({
        title: "Потвърди",
        message: "Желаеш ли да изтриеш тази доставка?",
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
            router.delete(route("deliveries.destroy", delivery_id), {
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

onMounted(() => {
    Object.values(usePage().props.errors)
        .flat()
        .forEach((error) => {
            $q.notify({
                message: error,
                icon: "mdi-alert-circle-outline",
                type: "negative",
            });
        });
});
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
                            :rows="deliveries.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="deliveriesIndex"
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
                            <template v-slot:body="props">
                                <q-tr
                                    :props="props"
                                    :class="
                                        props.row.status === 1 ? 'bg-red-3' : ''
                                    "
                                >
                                    <q-td
                                        v-for="col in props.cols"
                                        :key="col.name"
                                        :props="props"
                                    >
                                        <div v-if="col.name === 'actions'">
                                            <q-btn
                                                v-if="hasPermission('view')"
                                                icon="mdi-archive-eye-outline"
                                                color="primary"
                                                title="Преглед на доставката"
                                                dense
                                                flat
                                                rounded
                                                @click="
                                                    router.get(
                                                        route(
                                                            'deliveries.show',
                                                            props.row.id
                                                        )
                                                    )
                                                "
                                            />
                                            <q-btn
                                                v-if="
                                                    hasPermission('update') &&
                                                    props.row.status === 0
                                                "
                                                icon="mdi-pencil-outline"
                                                color="primary"
                                                title="Промяна на доставката"
                                                dense
                                                flat
                                                rounded
                                                @click="
                                                    router.get(
                                                        route(
                                                            'deliveries.edit',
                                                            props.row.id
                                                        )
                                                    )
                                                "
                                            />
                                            <q-btn
                                                v-if="
                                                    hasPermission('delete') &&
                                                    props.row.status === 0
                                                "
                                                icon="mdi-delete-outline"
                                                color="negative"
                                                title="Изтриване на доставката"
                                                dense
                                                flat
                                                rounded
                                                @click="confirm(props.row.id)"
                                            />
                                        </div>
                                        <div v-else-if="col.name === 'status'">
                                            {{
                                                props.row["status"] === 0
                                                    ? "Типов"
                                                    : "Приключен"
                                            }}
                                        </div>
                                        <div
                                            v-else-if="
                                                col.name === 'created_at'
                                            "
                                        >
                                            {{
                                                moment(
                                                    props.row["created_at"]
                                                ).format("DD.MM.YY HH:mm")
                                            }}
                                        </div>
                                        <div v-else-if="col.name === 'total'">
                                            {{
                                                props.row.subdeliveries
                                                    .reduce((sum, item) => {
                                                        return (
                                                            sum +
                                                            parseFloat(
                                                                item.quantity
                                                            ) *
                                                                parseFloat(
                                                                    item.price
                                                                )
                                                        );
                                                    }, 0)
                                                    .toFixed(2)
                                            }}
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

                    <q-btn
                        v-if="hasPermission('create')"
                        color="primary"
                        label="Нова доставка"
                        icon="mdi-file-document-plus-outline"
                        @click="router.get(route('deliveries.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
