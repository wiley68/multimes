<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router } from '@inertiajs/vue3'

defineProps(['roles'])

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
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const rolesCreate = () => { router.get(route('roles.create')) }
</script>

<template>

    <Head title="Роли"></Head>

    <AdminLayout>
        <q-page class="q-pa-md">
            <div class="flex justify-between mb-2">
                <h5>Управление на роли</h5>
                <q-btn
                    color="primary"
                    label="Нова роля"
                    @click="rolesCreate"
                />
            </div>
            <q-table
                class="my-sticky-header-table"
                flat
                bordered
                title="Роли"
                rows-per-page="10"
                rows-per-page-label="Записи на страница"
                :rows="roles"
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
