<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { useQuasar } from "quasar";
import { usePermission } from "@/composables/permissions";
import moment from "moment";
import * as XLSX from "xlsx";
import { saveAs } from "file-saver";

const props = defineProps({
    productions: {
        type: Object,
    },
    filter: {
        type: String,
    },
    hall: {
        type: String,
    },
    type: {
        type: String,
    },
    hall_name: {
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
        style: "width:60px;",
        sortable: false,
        sortMethod: (a, b) => (a < b ? -1 : a > b ? 1 : 0),
    },
    {
        name: "hall_name",
        align: "left",
        label: "Хале",
        sortable: false,
    },
    {
        name: "type",
        align: "left",
        label: "Тип",
        sortable: false,
    },
    {
        name: "group_number",
        align: "left",
        label: "Група",
        field: "group_number",
        sortable: false,
    },
    {
        name: "partida_number",
        align: "left",
        label: "Партида",
        field: "partida_number",
        sortable: false,
    },
    {
        name: "status",
        align: "left",
        label: "Състояние",
        sortable: false,
    },
    {
        name: "created_at",
        align: "left",
        label: "Стариран на",
        sortable: false,
    },
    {
        name: "finished_at",
        align: "left",
        label: "Приключен на",
        sortable: false,
    },
    {
        name: "product",
        align: "left",
        label: "Прасета",
        sortable: false,
    },
    {
        name: "result",
        align: "left",
        label: "Резултат",
        sortable: false,
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        sortable: false,
    },
];

const isSortColumn = (col) => {
    return [
        "id",
        "hall_name",
        "group_number",
        "partida_number",
        "status",
        "created_at",
        "finished_at",
        "stock",
        "price",
    ].includes(col);
};

const title = "Всички Производствени Процеси";
const { hasPermission } = usePermission();
const $q = useQuasar();
const pagination = ref({
    page: props.productions.current_page,
    rowsPerPage: props.productions.per_page,
    rowsNumber: props.productions.total,
    sortBy: props.sortBy,
    sortOrder: props.sortOrder,
});

const filter = ref(props.filter);

const productionsIndex = (requestProp) => {
    router.get(
        route("reports.index"),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: pagination.value.sortBy,
            sortOrder: pagination.value.sortOrder,
            filter: filter.value,
            hall: props.hall,
            type: props.type,
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
    productionsIndex({ pagination });
};

const mproductionShow = (mproduction) => {
    router.get(route("mproductions.show", mproduction), {
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
};

const uproductionShow = (uproduction) => {
    router.get(route("uproductions.show", uproduction), {
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
};

const totalResult = computed(() => {
    return props.productions.data
        .reduce(
            (total, item) =>
                total + (item.increments_result - item.decrements_result),
            0
        )
        .toFixed(2);
});

const exportToExcel = () => {
    const ws = XLSX.utils.json_to_sheet(props.productions.data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Productions");
    const wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
    saveAs(
        new Blob([wbout], { type: "application/octet-stream" }),
        "productions.xlsx"
    );
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
                            :title="
                                hall_name
                                    ? `Процеси Майки в Хале: ${hall_name}`
                                    : title
                            "
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="productions.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="productionsIndex"
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
                                        <div
                                            v-if="col.name === 'hall_name'"
                                            class="multiline-text"
                                        >
                                            {{ props.row.hall_name }}
                                        </div>
                                        <div
                                            v-else-if="col.name === 'type'"
                                            style="width: 60px"
                                            class="multiline-text"
                                        >
                                            {{
                                                props.row.type === "M"
                                                    ? "Майки"
                                                    : "Угояване"
                                            }}
                                        </div>
                                        <div
                                            v-else-if="col.name === 'status'"
                                            style="width: 80px"
                                        >
                                            {{
                                                props.row.status === 1
                                                    ? "Активен"
                                                    : "Приключен"
                                            }}
                                        </div>
                                        <div
                                            v-else-if="
                                                col.name === 'created_at'
                                            "
                                            style="width: 60px"
                                        >
                                            {{
                                                moment(
                                                    props.row.created_at
                                                ).format("DD.MM.YY HH:mm")
                                            }}
                                        </div>
                                        <div
                                            v-else-if="
                                                col.name === 'finished_at'
                                            "
                                            style="width: 60px"
                                        >
                                            {{
                                                props.row.finished_at === null
                                                    ? ""
                                                    : moment(
                                                          props.row[
                                                              "finished_at"
                                                          ]
                                                      ).format("DD.MM.YY HH:mm")
                                            }}
                                        </div>
                                        <div
                                            v-else-if="col.name === 'product'"
                                            class="multiline-text"
                                        >
                                            {{
                                                props.row.product_id
                                                    ? `[${props.row.product_nomenklature}]
                                            ${props.row.product_name}`
                                                    : ""
                                            }}
                                        </div>
                                        <div
                                            v-else-if="col.name === 'result'"
                                            style="width: 60px"
                                        >
                                            {{
                                                (
                                                    props.row
                                                        .increments_result -
                                                    props.row.decrements_result
                                                ).toFixed(2)
                                            }}
                                        </div>
                                        <div
                                            v-else-if="col.name === 'actions'"
                                            style="width: 60px"
                                        >
                                            <q-btn
                                                v-if="hasPermission('view')"
                                                title="Управлявай процеса"
                                                icon="mdi-file-tree"
                                                color="primary"
                                                dense
                                                flat
                                                rounded
                                                @click="
                                                    props.row.type === 'M'
                                                        ? mproductionShow(
                                                              props.row.id
                                                          )
                                                        : uproductionShow(
                                                              props.row.id
                                                          )
                                                "
                                            />
                                        </div>
                                        <div v-else>
                                            {{ props.row[col.name] }}
                                        </div>
                                    </q-td>
                                </q-tr>
                            </template>
                            <template v-slot:bottom-row>
                                <q-tr>
                                    <q-td colspan="9" class="text-weight-bold"
                                        >Общо:</q-td
                                    >
                                    <q-td class="text-weight-bold">
                                        {{ totalResult }}
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
                    /><q-btn
                        label="Експорт в Excel"
                        color="primary"
                        @click="exportToExcel"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
