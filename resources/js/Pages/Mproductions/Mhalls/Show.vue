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
        name: 'factory',
        align: 'left',
        label: 'База',
        field: row => row.factory.name,
        sortable: true
    },
    { name: 'name', align: 'left', label: 'Име', field: 'name', sortable: true },
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
                :rows="mhalls.data"
                :columns="columns"
                row-key="name"
                :filter="filter"
                hide-header
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
                    <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4">
                        <q-card
                            flat
                            bordered
                        >
                            <q-card-section class="text-center">
                                Име
                                <br>
                                <strong>{{ props.row.name }}</strong>
                            </q-card-section>
                            <q-separator />
                            <q-card-section class="flex flex-center">
                                <div>{{ props.row.factory.name }}</div>
                            </q-card-section>
                            <q-separator />
                            <q-card-section class="flex flex-center">
                                <div>{{ props.row.factory.name }}</div>
                            </q-card-section>
                        </q-card>
                    </div>
                </template>
            </q-table>
        </div>
    </DefaultLayout>
</template>
