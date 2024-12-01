<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps(['users'])

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
    { name: 'name', align: 'center', label: 'Име', field: 'name', sortable: true },
    { name: 'email', label: 'email', field: 'email', sortable: true },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]
</script>

<template>

    <Head title="Потребители"></Head>

    <AdminLayout>
        <q-page class="q-pa-md">
            <q-table
                class="my-sticky-header-table"
                flat
                bordered
                title="Потребители"
                rows-per-page="10"
                rows-per-page-label="Записи на страница"
                :rows="users"
                :columns="columns"
                row-key="id"
            >
                <template v-slot:body-cell-actions="props">
                    <q-td align="center">
                        <q-btn
                            icon="edit"
                            color="primary"
                            dense
                            flat
                            rounded
                            @click="console.log(props.row.id)"
                        />
                        <q-btn
                            icon="delete"
                            color="negative"
                            dense
                            flat
                            rounded
                            @click="console.log(props.row.id)"
                        />
                    </q-td>
                </template>
            </q-table>
        </q-page>
    </AdminLayout>
</template>
