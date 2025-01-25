<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'

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
        sortable: true,
    },
    {
        name: 'factory_id',
        align: 'left',
        label: 'База',
        field: row => row.factory.name,
        format: val => `${val}`,
        sortable: true,
    },
    {
        name: 'silo_id',
        align: 'left',
        label: 'Силоз',
        field: row => row.silo.name,
        format: val => `${val}`,
        sortable: true,
    },
    {
        name: 'name',
        align: 'left',
        label: 'Име',
        field: 'name',
        sortable: true,
    },
]

const pagination = {
    page: props.mhalls.meta.current_page,
    rowsPerPage: props.mhalls.meta.per_page,
    rowsNumber: props.mhalls.meta.total
}
const title = 'Халета Майки'
const filter = ref(props.filter)
const $q = useQuasar()
const { hasPermission } = usePermission()

const getDaysBetweenTodayAndDate = (targetDate) => {
    const today = moment().startOf('day')
    const target = moment(targetDate).startOf('day')
    return today.diff(target, 'days')
}

const productionPurcent = (mproduction) => {
    const days = getDaysBetweenTodayAndDate(mproduction.created_at)
    return parseFloat((days / parseFloat(mproduction.production_days)).toFixed(2))
}
const productionPurcentLabel = (mproduction) => { return `${(productionPurcent(mproduction) * 100).toFixed(2)}%` }

const onRequest = (requestProp) => {
    router.get(
        route('mhalls.show'),
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

const confirm = (mhall) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Ще бъде стартиран нов производствен процес! Процеса е необратим. Съгласен ли сте с това?',
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
        router.post(route('mproductions.store'), {
            status: 1,
            mhall: mhall,
            production_days: 45,
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
                            grid
                            grid-header
                            title="Халета Майки"
                            rows-per-page-label="Записи на страница"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            :rows="mhalls.data"
                            :columns="columns"
                            row-key="name"
                            :pagination="pagination"
                            :rows-per-page-options="[6, 9, 12, 15, 18, 0]"
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
                                <div class="q-pa-md col-xs-12 col-sm-6 col-md-4">
                                    <q-card
                                        flat
                                        bordered
                                    >
                                        <q-card-section
                                            class="text-center text-white"
                                            :class="props.row.mproduction !== null ? 'bg-accent' : 'bg-grey'"
                                        >
                                            <div class="text-h6">Хале: {{ props.row.name }}</div>
                                            <template v-if="props.row.mproduction !== null">
                                                <div class="text-subtitle2">Активен производствен процес: №{{
                                                    props.row.mproduction.id
                                                }}
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div class="text-subtitle2">Няма активен производствен процес</div>
                                            </template>
                                        </q-card-section>
                                        <q-separator />
                                        <q-card-section class="columns flex-center">
                                            <div>База: {{ props.row.factory.name }}</div>
                                            <div>Силоз: {{ props.row.silo.name }}</div>
                                            <div><strong>Прасета</strong>: {{
                                                props.row.mproduction ? props.row.mproduction.stock : 0 }}</div>
                                            <template v-if="props.row.mproduction !== null">
                                                <div
                                                    class="q-py-sm q-ma-none"
                                                    style="height: 36px;"
                                                >
                                                    <q-linear-progress
                                                        class="full-height full-width"
                                                        :value="productionPurcent(props.row.mproduction)"
                                                        color="accent"
                                                        rounded
                                                    >
                                                        <div class="absolute-full flex flex-center">
                                                            <q-badge
                                                                color="white"
                                                                text-color="accent"
                                                                :label="productionPurcentLabel(props.row.mproduction)"
                                                            />
                                                        </div>
                                                    </q-linear-progress>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div
                                                    class="q-py-sm q-ma-none"
                                                    style="height: 36px;"
                                                ></div>
                                            </template>
                                        </q-card-section>
                                        <q-separator />
                                        <q-card-actions align="around">
                                            <template v-if="props.row.mproduction !== null">
                                                <q-btn
                                                    flat
                                                    :class="props.row.mproduction !== null ? 'text-accent' : ''"
                                                    @click="router.get(route('mproductions.show', { mproduction: props.row.mproduction }))"
                                                >Управлявай процеса</q-btn>
                                            </template>
                                            <template v-else>
                                                <q-btn
                                                    v-if="hasPermission('create')"
                                                    @click="confirm(props.row)"
                                                    flat
                                                >Стартирай процес</q-btn>
                                            </template>
                                            <q-btn
                                                flat
                                                @click="router.get(route('mproductions.index', { mhall: props.row.id }))"
                                            >Виж процеси</q-btn>
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
                        @click="router.get(route('mproductions.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
