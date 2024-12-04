<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { useQuasar } from 'quasar'

const props = defineProps({
    users: {
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
    { name: 'name', align: 'left', label: 'Име', field: 'name', sortable: true },
    { name: 'email', align: 'left', label: 'Имейл', field: 'email', sortable: true },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const $q = useQuasar()
const pagination = {
    page: props.users.meta.current_page,
    rowsPerPage: props.users.meta.per_page,
    rowsNumber: props.users.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)

const onRequest = (requestProp) => {
    router.get(
        route('users.index'),
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

const confirm = (user_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш потребителя?',
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
        router.delete(route('users.destroy', user_id))
    }).onOk(() => { }).onCancel(() => { }).onDismiss(() => { })
}
</script>

<template>

    <Head title="Потребители"></Head>

    <AdminLayout>
        <q-page class="q-pa-md">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Табло"
                        icon="chevron_left"
                        @click="router.get(route('admin.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Управление на потребители</h5>
                <div class="col row justify-end items-center">
                    <q-btn
                        color="primary"
                        label="Нов потребител"
                        icon="add"
                        @click="router.get(route('users.create'))"
                    />
                </div>
            </div>
            <q-table
                ref="tableRef"
                class="my-sticky-header-table"
                :class="tableClass"
                bordered
                title="Потребители"
                rows-per-page-label="Записи на страница"
                separator="cell"
                no-data-label="Липсват данни"
                no-results-label="Няма съответстващи записи"
                loading-label="Данните се зареждат..."
                table-header-class="bg-grey-3"
                :rows="users.data"
                :columns="columns"
                row-key="id"
                :pagination="pagination"
                :filter="filter"
                @request="onRequest"
                @focusin="activateNavigation"
                @focusout="deactivateNavigation"
            >
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
                            <q-icon name="search" />
                        </template>
                    </q-input>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td align="center">
                        <q-btn
                            icon="edit"
                            color="primary"
                            dense
                            flat
                            rounded
                            @click="router.get(route('users.edit', props.row.id))"
                        />
                        <q-btn
                            icon="delete"
                            color="negative"
                            dense
                            flat
                            rounded
                            @click="confirm(props.row.id)"
                        />
                    </q-td>
                </template>
            </q-table>
        </q-page>
    </AdminLayout>
</template>
