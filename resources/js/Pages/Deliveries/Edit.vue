<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    delivery: {
        type: Object,
        required: true
    }
})

const form = useForm({
    document: props.delivery?.document,
    supplier: props.delivery?.supplier,
    status: props.delivery?.status === 0 ? { label: 'Типов документ', value: 0 } : { label: 'Приключен документ', value: 1 },
})

const onSubmit = () => {
    form.put(route('deliveries.update', props.delivery.id), {
        onFinish: () => {
            form.reset('document', 'supplier', 'status')
        },
    })
};

const subdeliveryColumns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: row => row.id,
        format: val => `${val}`,
        sortable: true
    },
    { name: 'product_id', align: 'left', label: 'Продукт', field: 'product_id', sortable: true },
    { name: 'quantity', align: 'left', label: 'Количество', field: 'quantity', sortable: true },
    { name: 'price', align: 'left', label: 'Цена', field: 'price', sortable: true },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const title = 'Доставка'
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
                        <div class="column q-gutter-y-md flex-grow flex-center">
                            <q-card class="q-pa-md full-width">
                                <q-form
                                    class="row q-gutter-xl"
                                    autofocus
                                >
                                    <q-input
                                        v-model="form.document"
                                        class="col"
                                        label="Документ номер"
                                        hint="Номер на насрещния документ за доставка"
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.document"
                                    />

                                    <q-input
                                        v-model="form.supplier"
                                        class="col"
                                        label="Доставчик"
                                        hint="Доставчик на продуктите"
                                    />
                                </q-form>
                            </q-card>

                            <q-table
                                class="my-sticky-header-table full-width"
                                bordered
                                hide-header
                                rows-per-page-label="Записи на страница"
                                separator="cell"
                                no-data-label="Липсват данни"
                                no-results-label="Няма съответстващи записи"
                                loading-label="Данните се зареждат..."
                                table-header-class="bg-grey-3"
                                :rows="delivery.subdeliveries"
                                :columns="subdeliveryColumns"
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
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Доставки"
                        title="Излиза без записване на промените. Връща към доставки."
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('deliveries.index'))"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши"
                        title="Записва направените промени в документа."
                        icon="mdi-content-save-outline"
                        color="primary"
                    />

                    <q-btn
                        label="Добави продукт"
                        title="Добавя нов продукт към документа."
                        icon="mdi-table-row-plus-after"
                        color="secondary"
                    />

                    <q-btn
                        label="Приключи"
                        title="Приключва документа. Вписва всички количества продукти. Обновява цените на продуктите."
                        icon="mdi-file-document-check-outline"
                        color="secondary"
                    />

                    <q-btn
                        label="Изтрий"
                        title="Изтрива документа. Операцията е необратима."
                        icon="mdi-delete-outline"
                        color="negative"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>