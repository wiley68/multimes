<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { onMounted, watch } from 'vue';
import VueMultiselect from 'vue-multiselect'

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    roles: Array,
    permissions: Array
})

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    password: '',
    roles: [],
    permissions: []
})

const roleColumns = [
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

const permissionColumns = [
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
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        onFinish: () => {
            form.password = ''
        },
    })
};

onMounted(() => {
    form.roles = props.user.roles
    form.permissions = props.user.permissions
})

watch(
    () => props.user,
    () => {
        form.roles = props.user.roles
        form.permissions = props.user.permissions
    }
)

const title = 'Потребител'
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
                                        label="Потребител *"
                                        hint="Имена на потребителя"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />

                                    <q-input
                                        v-model="form.email"
                                        label="Имейл *"
                                        hint="Имейл на потребителя"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.email"
                                    />

                                    <q-input
                                        v-model="form.password"
                                        type="password"
                                        label="Нова парола"
                                        hint="Нова парола на потребителя (не е задължителна)"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.password"
                                    />

                                    <div class="q-my-lg">
                                        <VueMultiselect
                                            v-model="form.roles"
                                            :options="roles"
                                            :multiple="true"
                                            :close-on-select="true"
                                            placeholder="Добави роли"
                                            label="name"
                                            track-by="name"
                                        />
                                    </div>

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
                                    class="my-sticky-header-table"
                                    bordered
                                    title="Роли към този потребител"
                                    rows-per-page-label="Записи на страница"
                                    separator="cell"
                                    no-data-label="Липсват данни"
                                    no-results-label="Няма съответстващи записи"
                                    loading-label="Данните се зареждат..."
                                    table-header-class="bg-grey-3"
                                    :rows="user.roles"
                                    :columns="roleColumns"
                                    row-key="id"
                                    :rows-per-page-options=[3]
                                >
                                    <template v-slot:body-cell-actions="props">
                                        <q-td align="center">
                                            <q-btn
                                                @click.prevent="router.delete(route('users.roles.destroy', [user.id, props.row.id]), { preserveScroll: true, })"
                                                label="Отмени"
                                                flat
                                                color="negative"
                                                dense
                                            />
                                        </q-td>
                                    </template>
                                </q-table>
                            </div>

                            <div class="q-mt-md full-width">
                                <q-table
                                    class="my-sticky-header-table"
                                    bordered
                                    title="Права към този потребител"
                                    rows-per-page-label="Записи на страница"
                                    separator="cell"
                                    no-data-label="Липсват данни"
                                    no-results-label="Няма съответстващи записи"
                                    loading-label="Данните се зареждат..."
                                    table-header-class="bg-grey-3"
                                    :rows="user.permissions"
                                    :columns="permissionColumns"
                                    row-key="id"
                                    :rows-per-page-options=[3]
                                >
                                    <template v-slot:body-cell-actions="props">
                                        <q-td align="center">
                                            <q-btn
                                                @click.prevent="router.delete(route('users.permissions.destroy', [user.id, props.row.id]), { preserveScroll: true, })"
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
                        label="Потребители"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('users.index'))"
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