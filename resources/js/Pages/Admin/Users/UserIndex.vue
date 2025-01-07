<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue';
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
        field: 'id',
        style: 'width: 60px;',
        sortable: true,
    },
    {
        name: 'name',
        align: 'left',
        label: 'Име',
        field: 'name',
        style: 'width: 200px;',
        sortable: true,
    },
    {
        name: 'email',
        align: 'left',
        label: 'Имейл',
        field: 'email',
        style: 'width: 200px;',
        sortable: true,
    },
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

const title = 'Потребители'
const $q = useQuasar()
const pagination = {
    page: props.users.meta.current_page,
    rowsPerPage: props.users.meta.per_page,
    rowsNumber: props.users.meta.total
}
const filter = ref(props.filter)

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
        router.delete(route('users.destroy', user_id), {
            onError: errors => {
                Object.values(errors).flat().forEach((error) => {
                    $q.notify({
                        message: error,
                        icon: 'mdi-alert-circle-outline',
                        type: 'negative',
                    });
                });
            },
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
                            class="my-sticky-header-table"
                            bordered
                            :title="title"
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
                                <q-td
                                    align="center"
                                    style="width: 80px;"
                                >
                                    <q-btn
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        title="Промяна на потребителя"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('users.edit', props.row.id))"
                                    />
                                    <q-btn
                                        v-if="$page.props.auth.user.id !== props.row.id"
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        title="Изтриване на потребителя"
                                        dense
                                        flat
                                        rounded
                                        @click="confirm(props.row.id)"
                                    />
                                </q-td>
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
                        @click="router.get(route('admin.index'))"
                    />

                    <q-btn
                        color="primary"
                        label="Нов потребител"
                        icon="mdi-account-plus-outline"
                        @click="router.get(route('users.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
