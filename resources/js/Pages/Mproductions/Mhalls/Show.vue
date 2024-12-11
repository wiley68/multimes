<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';

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
    { name: 'name', align: 'left', label: 'Име', field: 'name', sortable: true },
    {
        name: 'status',
        align: 'left',
        label: 'Състояние процес',
        field: row => row.mproductions,
        format: val => {
            if (Array.isArray(val) && val.length > 0) {
                const foundMproject = val.find(item => item.status === true)
                if (foundMproject) {
                    return foundMproject.name
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        sortable: false
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const pagination = {
    page: props.mhalls.meta.current_page,
    rowsPerPage: props.mhalls.meta.per_page,
    rowsNumber: props.mhalls.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)

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
</script>

<template>

    <Head title="Всички Халета Майки"></Head>

    <DefaultLayout>
        <div class="q-pa-md">
            <q-table
                grid
                grid-header
                flat
                bordered
                title="Халета Майки"
                :class="tableClass"
                :rows="mhalls.data"
                :columns="columns"
                row-key="name"
                :pagination="pagination"
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
                                :class="props.row.status ? 'bg-accent' : 'bg-grey'"
                            >
                                <div class="text-h6">{{ props.row.name }}</div>
                                <template v-if="props.row.status">
                                    <div class="text-subtitle2">Активен процес: {{ props.row.status }}</div>
                                </template>
                                <template v-else>
                                    <div class="text-subtitle2">Няма активен процес</div>
                                </template>
                            </q-card-section>
                            <q-separator />
                            <q-card-section class="flex flex-center">
                                <div>{{ props.row.factory.name }}</div>
                            </q-card-section>
                            <q-separator />
                            <q-card-actions align="around">
                                <template v-if="props.row.status">
                                    <q-btn flat>Управлявай процеса</q-btn>
                                </template>
                                <template v-else>
                                    <q-btn flat>Стартирай процес</q-btn>
                                </template>
                                <q-btn flat>Всички процеси</q-btn>
                            </q-card-actions>
                        </q-card>
                    </div>
                </template>
            </q-table>
        </div>
    </DefaultLayout>
</template>
