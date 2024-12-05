<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { onMounted } from 'vue';
import VueMultiselect from 'vue-multiselect'

const props = defineProps({
    role: {
        type: Object,
        required: true
    },
    permissions: Array
})

const form = useForm({
    name: props.role?.name,
    permissions: []
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
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const onSubmit = () => {
    form.put(route('roles.update', props.role.id), {
        onFinish: () => form.reset('name'),
    })
};

const onReset = () => {
    form.reset('name')
}

onMounted(() => {
    form.permissions = props.role.permissions
})
</script>

<template>

    <Head title="Редакция на роля"></Head>

    <AdminLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Роли"
                        icon="chevron_left"
                        @click="router.get(route('roles.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Редакция на роля</h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">
                <q-card
                    class="q-pa-md"
                    style="width: 600px;"
                >
                    <q-form
                        @submit.prevent="onSubmit"
                        @reset="onReset"
                        class="q-gutter-md"
                    >
                        <q-input
                            v-model="form.name"
                            label="Роля *"
                            hint="Име на ролята"
                            :error="form.hasErrors"
                            :error-message="form.errors.name"
                        />

                        <div class="q-my-lg">
                            <VueMultiselect
                                v-model="form.permissions"
                                :options="permissions"
                                :multiple="true"
                                :close-on-select="true"
                                placeholder="Добави права"
                                label="name"
                                track-by="name"
                            />
                        </div>

                        <div>
                            <q-btn
                                label="Промени"
                                type="submit"
                                color="primary"
                            />
                            <q-btn
                                label="Откажи"
                                type="reset"
                                color="primary"
                                flat
                                class="q-ml-sm"
                            />
                        </div>
                    </q-form>
                </q-card>

                <div
                    class="q-mt-md"
                    style="width: 600px;"
                >
                    <q-table
                        ref="tableRef"
                        class="my-sticky-header-table"
                        bordered
                        title="Права към тази роля"
                        rows-per-page-label="Записи на страница"
                        separator="cell"
                        no-data-label="Липсват данни"
                        no-results-label="Няма съответстващи записи"
                        loading-label="Данните се зареждат..."
                        table-header-class="bg-grey-3"
                        :rows="role.permissions"
                        :columns="columns"
                        row-key="id"
                        :rows-per-page-options=[3]
                    >
                        <template v-slot:body-cell-actions="props">
                            <q-td align="center">
                                <q-btn
                                    label="Отмени"
                                    flat
                                    color="negative"
                                    dense
                                />
                            </q-td>
                        </template>
                    </q-table>
                </div>
            </div>
        </q-page>
    </AdminLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>