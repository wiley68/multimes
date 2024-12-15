<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
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
        field: row => row.id,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'factory_id',
        align: 'left',
        label: 'База',
        field: row => row.factory.name,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'silo_id',
        align: 'left',
        label: 'Силоз',
        field: row => row.silo.name,
        format: val => `${val}`,
        sortable: true
    },
    { name: 'name', align: 'left', label: 'Име', field: 'name', sortable: true }
]

const pagination = {
    page: props.mhalls.meta.current_page,
    rowsPerPage: props.mhalls.meta.per_page,
    rowsNumber: props.mhalls.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)
const $q = useQuasar()
const { hasPermission } = usePermission()

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

const activateNavigation = () => {
    navigationActive.value = true
}

const deactivateNavigation = () => {
    navigationActive.value = false
}

const tableClass = computed(() => navigationActive.value === true ? 'shadow-8 no-outline' : null)

const checkStatus = (val) => {
    if (Array.isArray(val) && val.length > 0) {
        const foundMproject = val.find(item => item.status === 1)
        if (foundMproject === undefined) {
            return false
        } else {
            return foundMproject.id
        }
    } else {
        return false
    }
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
        })
    }).onCancel(() => { }).onDismiss(() => { })
}
</script>

<template>

    <Head title="Всички Халета Майки"></Head>

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
                <h5 class="col row justify-center items-center">Халета Майки</h5>
                <div class="col row justify-end items-center">
                    <q-btn
                        label="Всички процеси"
                        icon="mdi-timer-play-outline"
                        color="primary"
                        @click="router.get(route('mproductions.index'))"
                    />
                </div>
            </div>

            <q-table
                grid
                grid-header
                flat
                bordered
                title="Халета Майки"
                rows-per-page-label="Записи на страница"
                no-data-label="Липсват данни"
                no-results-label="Няма съответстващи записи"
                loading-label="Данните се зареждат..."
                :class="tableClass"
                :rows="mhalls.data"
                :columns="columns"
                row-key="name"
                :pagination="pagination"
                :rows-per-page-options="[6, 9, 12, 15, 18, 0]"
                :filter="filter"
                hide-header
                @request="onRequest"
                @focusin="activateNavigation"
                @focusout="deactivateNavigation"
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
                                :class="checkStatus(props.row.mproductions) ? 'bg-accent' : 'bg-grey'"
                            >
                                <div class="text-h6">Хале: {{ props.row.name }}</div>
                                <template v-if="checkStatus(props.row.mproductions)">
                                    <div class="text-subtitle2">Активен производствен процес: №{{
                                        checkStatus(props.row.mproductions)
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
                            </q-card-section>
                            <q-separator />
                            <q-card-actions align="around">
                                <template v-if="checkStatus(props.row.mproductions)">
                                    <q-btn
                                        flat
                                        :class="checkStatus(props.row.mproductions) ? 'text-accent' : ''"
                                        @click="router.get(route('mproductions.show', { mproduction: checkStatus(props.row.mproductions) }))"
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
        </q-page>
    </DefaultLayout>
</template>
