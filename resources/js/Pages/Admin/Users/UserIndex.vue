<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
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
        name: 'roles',
        align: 'left',
        label: 'Роли',
        field: row => row.roles.map(obj => obj.name).join(', '),
        format: val => `${val}`,
        sortable: false
    },
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

    <DefaultLayout>
        <q-page class="q-pa-md">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Табло"
                        icon="mdi-menu-left"
                        @click="router.get(route('admin.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Управление на потребители</h5>
                <div class="col row justify-end items-center">
                    <q-btn
                        color="primary"
                        label="Нов потребител"
                        icon="mdi-plus"
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
                            <q-icon name="mdi-magnify" />
                        </template>
                    </q-input>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td align="center">
                        <q-btn
                            icon="mdi-pencil-outline"
                            color="primary"
                            dense
                            flat
                            rounded
                            @click="router.get(route('users.edit', props.row.id))"
                        />
                        <q-btn
                            v-if="$page.props.auth.user.id !== props.row.id"
                            icon="mdi-delete-outline"
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
    </DefaultLayout>
</template>
