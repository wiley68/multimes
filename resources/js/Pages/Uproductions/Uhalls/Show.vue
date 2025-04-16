<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { usePermission } from "@/composables/permissions";
import moment from "moment";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";

const props = defineProps({
    uhalls: {
        type: Object,
    },
    filter: {
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
        sortable: true,
    },
    {
        name: "factory_id",
        align: "left",
        label: "База",
        field: (row) => row.factory.name,
        format: (val) => `${val}`,
        sortable: true,
    },
    {
        name: "silo_id",
        align: "left",
        label: "Силоз",
        field: (row) => row.silo.name,
        format: (val) => `${val}`,
        sortable: true,
    },
    {
        name: "name",
        align: "left",
        label: "Име",
        field: "name",
        sortable: true,
    },
];

const pagination = {
    page: props.uhalls.meta.current_page,
    rowsPerPage: props.uhalls.meta.per_page,
    rowsNumber: props.uhalls.meta.total,
};

const title = "Халета Угояване";
const filter = ref(props.filter);
const { hasPermission } = usePermission();

const getDaysBetweenTodayAndDate = (targetDate) => {
    const today = moment().startOf("day");
    const target = moment(targetDate).startOf("day");
    return today.diff(target, "days");
};

const productionPurcent = (uproduction) => {
    const days = getDaysBetweenTodayAndDate(uproduction.created_at);
    return parseFloat(
        (days / parseFloat(uproduction.production_days)).toFixed(2)
    );
};
const productionPurcentLabel = (uproduction) => {
    return `${(productionPurcent(uproduction) * 100).toFixed(2)}%`;
};

const onRequest = (requestProp) => {
    router.get(
        route("uhalls.show"),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: requestProp.pagination.sortBy,
            sortOrder: requestProp.pagination.descending ? "desc" : "asc",
            filter: filter.value,
        },
        {
            preserveState: false,
        }
    );
};

const showPrompt = ref(false);
const selectedUhall = ref(null);

const confirm = (uhall) => {
    selectedUhall.value = uhall;
    showPrompt.value = true;
};

const handleOk = (data) => {
    router.post(route("uproductions.store"), {
        status: 1,
        uhall: selectedUhall.value,
        production_days: 45,
        group_number: data.groupNumber,
        partida_number: data.partidaNumber,
    });

    showPrompt.value = false;
};

const handleCancel = () => {
    showPrompt.value = false;
};
</script>

<template>
    <Head :title="title"></Head>

    <DefaultLayout :title="title" icon="mdi-file-document-outline">
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <ConfirmDialog
                            :show="showPrompt"
                            title="Потвърди"
                            message="Ще бъде стартиран нов производствен процес! Процесът е необратим. Въведете номера на група и партида."
                            @ok="handleOk"
                            @cancel="handleCancel"
                        />
                        <q-table
                            grid
                            grid-header
                            title="Халета Угояване"
                            rows-per-page-label="Записи на страница"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            :rows="uhalls.data"
                            :columns="columns"
                            row-key="name"
                            :pagination="pagination"
                            :rows-per-page-options="[6, 9, 12, 15, 18, 48, 0]"
                            :filter="filter"
                            hide-header
                            @request="onRequest"
                        >
                            <template v-slot:top-right>
                                <q-input
                                    borderless
                                    dense
                                    autofocus
                                    debounce="300"
                                    v-model="filter"
                                    placeholder="Търси..."
                                >
                                    <template v-slot:append>
                                        <q-icon name="mdi-magnify" />
                                    </template>
                                </q-input>
                            </template>

                            <template v-slot:item="props">
                                <div
                                    class="q-pa-md col-xs-12 col-sm-6 col-md-4"
                                >
                                    <q-card flat bordered>
                                        <q-card-section
                                            class="text-center text-white"
                                            :class="
                                                props.row.uproduction !== null
                                                    ? 'bg-accent'
                                                    : 'bg-grey'
                                            "
                                        >
                                            <div class="text-h6">
                                                {{ props.row.name }}
                                            </div>
                                            <template
                                                v-if="
                                                    props.row.uproduction !==
                                                    null
                                                "
                                            >
                                                <div class="text-subtitle2">
                                                    Активен производствен
                                                    процес: №{{
                                                        props.row.uproduction.id
                                                    }}
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div class="text-subtitle2">
                                                    Няма активен производствен
                                                    процес
                                                </div>
                                            </template>
                                        </q-card-section>
                                        <q-separator />
                                        <q-card-section
                                            class="columns flex-center"
                                        >
                                            <div>
                                                <strong>База</strong>:
                                                {{ props.row.factory.name }}
                                            </div>
                                            <div>
                                                <strong>Силоз</strong>:
                                                {{ props.row.silo.name }}
                                            </div>
                                            <div>
                                                <strong>Прасета</strong>:
                                                {{
                                                    props.row.uproduction
                                                        ? props.row.uproduction
                                                              .stock
                                                        : 0
                                                }}
                                            </div>
                                            <template
                                                v-if="
                                                    props.row.uproduction !==
                                                    null
                                                "
                                            >
                                                <div
                                                    class="q-py-sm q-ma-none"
                                                    style="height: 36px"
                                                >
                                                    <q-linear-progress
                                                        class="full-height full-width"
                                                        :value="
                                                            productionPurcent(
                                                                props.row
                                                                    .uproduction
                                                            )
                                                        "
                                                        color="accent"
                                                        rounded
                                                    >
                                                        <div
                                                            class="absolute-full flex flex-center"
                                                        >
                                                            <q-badge
                                                                color="white"
                                                                text-color="accent"
                                                                :label="
                                                                    productionPurcentLabel(
                                                                        props
                                                                            .row
                                                                            .uproduction
                                                                    )
                                                                "
                                                            />
                                                        </div>
                                                    </q-linear-progress>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div
                                                    class="q-py-sm q-ma-none"
                                                    style="height: 36px"
                                                ></div>
                                            </template>
                                        </q-card-section>
                                        <q-separator />
                                        <q-card-actions align="around">
                                            <template
                                                v-if="
                                                    props.row.uproduction !==
                                                    null
                                                "
                                            >
                                                <q-btn
                                                    flat
                                                    :class="
                                                        props.row
                                                            .uproduction !==
                                                        null
                                                            ? 'text-accent'
                                                            : ''
                                                    "
                                                    @click="
                                                        router.get(
                                                            route(
                                                                'uproductions.show',
                                                                {
                                                                    uproduction:
                                                                        props
                                                                            .row
                                                                            .uproduction,
                                                                }
                                                            )
                                                        )
                                                    "
                                                    >Управлявай процеса</q-btn
                                                >
                                            </template>
                                            <template v-else>
                                                <q-btn
                                                    v-if="
                                                        hasPermission('create')
                                                    "
                                                    @click="confirm(props.row)"
                                                    flat
                                                    >Стартирай процес</q-btn
                                                >
                                            </template>
                                            <q-btn
                                                flat
                                                @click="
                                                    router.get(
                                                        route(
                                                            'uproductions.index',
                                                            {
                                                                uhall: props.row
                                                                    .id,
                                                            }
                                                        )
                                                    )
                                                "
                                                >Виж процеси</q-btn
                                            >
                                        </q-card-actions>
                                    </q-card>
                                </div>
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
                        label="Всички процеси"
                        icon="mdi-timer-play-outline"
                        color="primary"
                        @click="router.get(route('uproductions.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
