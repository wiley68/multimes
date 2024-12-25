<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { onMounted, watch } from 'vue';
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
        onFinish: () => {
            form.reset('name')
        },
    })
};

onMounted(() => {
    form.permissions = props.role.permissions
})

watch(
    () => props.role,
    () => form.permissions = props.role.permissions
)

const title = 'Роля'
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-edit-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <div class="column flex-grow flex-center">
                            <q-card class="q-pa-md full-width">
                                <q-form class="q-gutter-md">
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
                                </q-form>
                            </q-card>

                            <div class="q-mt-md full-width">
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
                                                @click.prevent="router.delete(route('roles.permissions.destroy', [role.id, props.row.id]), { preserveScroll: true, })"
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
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Роли"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('roles.index'))"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши"
                        icon="mdi-content-save-outline"
                        color="primary"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>