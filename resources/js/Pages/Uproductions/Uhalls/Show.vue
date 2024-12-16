<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    uhalls: {
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
    { name: 'name', align: 'left', label: 'Име', field: 'name', sortable: true },
]

const pagination = {
    page: props.uhalls.meta.current_page,
    rowsPerPage: props.uhalls.meta.per_page,
    rowsNumber: props.uhalls.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)
const $q = useQuasar()
const { hasPermission } = usePermission()

const onRequest = (requestProp) => {
    router.get(
        route('uhalls.show'),
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
        const foundUproject = val.find(item => item.status === 1)
        if (foundUproject === undefined) {
            return false
        } else {
            return foundUproject.id
        }
    } else {
        return false
    }
}

const confirm = (uhall) => {
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
        router.post(route('uproductions.store'), {
            status: 1,
            uhall: uhall,
        })
    }).onCancel(() => { }).onDismiss(() => { })
}
</script>

<template>

    <Head title="Всички Халета Угояване"></Head>

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
                <h5 class="col row justify-center items-center">Халета Угояване</h5>
                <div class="col row justify-end items-center">
                    <q-btn
                        label="Всички процеси"
                        icon="mdi-timer-play-outline"
                        color="primary"
                        @click="router.get(route('uproductions.index'))"
                    />
                </div>
            </div>

            <q-table
                grid
                grid-header
                flat
                bordered
                title="Халета Угояване"
                rows-per-page-label="Записи на страница"
                no-data-label="Липсват данни"
                no-results-label="Няма съответстващи записи"
                loading-label="Данните се зареждат..."
                :class="tableClass"
                :rows="uhalls.data"
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
                                :class="checkStatus(props.row.uproductions) ? 'bg-accent' : 'bg-grey'"
                            >
                                <div class="text-h6">{{ props.row.name }}</div>
                                <template v-if="checkStatus(props.row.uproductions)">
                                    <div class="text-subtitle2">Активен производствен процес: №{{
                                        checkStatus(props.row.uproductions)
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
                                <template v-if="checkStatus(props.row.uproductions)">
                                    <q-btn
                                        flat
                                        :class="checkStatus(props.row.uproductions) ? 'text-accent' : ''"
                                        @click="router.get(route('uproductions.show', { uproduction: checkStatus(props.row.uproductions) }))"
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
                                    @click="router.get(route('uproductions.index', { uhall: props.row.id }))"
                                >Виж процеси</q-btn>
                            </q-card-actions>
                        </q-card>
                    </div>
                </template>
            </q-table>
        </q-page>
    </DefaultLayout>
</template>
