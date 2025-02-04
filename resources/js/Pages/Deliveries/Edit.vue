<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import { computed, onMounted } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    delivery: {
        type: Object,
        required: true,
    }
})

const form = useForm({
    document: props.delivery?.document,
    supplier: props.delivery?.supplier,
    status: props.delivery?.status === 0 ? { label: 'Типов документ', value: 0 } : { label: 'Приключен документ', value: 1 },
})

const { hasPermission } = usePermission()

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
        sortable: true,
        style: "width: 40px;",
    },
    {
        name: 'nomenklature',
        align: 'left',
        label: 'Номенклаура',
        style: "width: 60px;",
        field: row => row.product.nomenklature,
        sortable: true,
    },
    {
        name: 'product',
        align: 'left',
        label: 'Продукт',
        field: row => row.product,
        format: val => `${val.name}`,
        sortable: true,
    },
    {
        name: 'type',
        align: 'left',
        label: 'Предназначение',
        style: "width: 60px;",
        field: row => row.product.type,
        sortable: true,
    },
    {
        name: 'quantity',
        align: 'left',
        label: 'Количество',
        field: 'quantity',
        sortable: true,
        style: "width: 80px;",
    },
    {
        name: 'me',
        align: 'left',
        label: 'м.е.',
        field: row => row.product,
        format: val => `${val.me}`,
        sortable: false,
        style: "width: 80px;",
    },
    {
        name: 'price',
        align: 'left',
        label: 'Цена',
        field: 'price',
        sortable: true,
        style: "width: 100px;",
    },
    {
        name: 'allprice',
        align: 'left',
        label: 'Общо',
        field: row => row,
        format: val => `${parseFloat(val.price * val.quantity).toFixed(2)}`,
        sortable: true,
        style: "width: 120px;",
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const $q = useQuasar()
onMounted(() => {
    Object.values(usePage().props.errors).flat().forEach((error) => {
        $q.notify({
            message: error,
            icon: 'mdi-alert-circle-outline',
            type: 'negative',
        });
    });
})

const totalPrice = computed(() => {
    return props.delivery.subdeliveries
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
});

const confirm = (subdelivery_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш реда в доставката?',
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
        router.delete(route('subdeliveries.destroy', subdelivery_id), {
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

const confirmDelivery = () => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш тази доставка?',
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
        router.delete(route('deliveries.destroy', props.delivery.id), {
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

const confirmCompletion = () => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да приключиш тази доставка? Всички продукти в доставката ще бъдат прехвърлени в склада, като ще променят текущите наличности и цени. Процеса е необратим!',
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
        form.status = { label: 'Приключен документ', value: 1 }
        form.patch(route('deliveries.complete', props.delivery.id), {
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

const title = `Доставка №${props.delivery.id}`
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
                                        :model-value="delivery.id"
                                        class="col"
                                        label="Доставка №"
                                        hint="Пореден номер на доставката"
                                        readonly
                                    />

                                    <q-input
                                        v-model="form.document"
                                        class="col"
                                        label="Документ номер"
                                        hint="Номер на документа за доставка"
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
                                rows-per-page-label="Записи на страница"
                                separator="cell"
                                no-data-label="Липсват данни"
                                no-results-label="Няма съответстващи записи"
                                loading-label="Данните се зареждат..."
                                table-header-class="bg-grey-3"
                                :rows="delivery.subdeliveries"
                                :columns="subdeliveryColumns"
                                row-key="id"
                                :rows-per-page-options=[7]
                            >
                                <template v-slot:body-cell-actions="props">
                                    <q-td
                                        align="center"
                                        style="width: 120px;"
                                    >
                                        <q-btn
                                            v-if="hasPermission('update')"
                                            icon="mdi-pencil-outline"
                                            color="primary"
                                            title="Промяна на реда"
                                            dense
                                            flat
                                            rounded
                                            @click="router.get(route('subdeliveries.edit', props.row.id))"
                                        />

                                        <q-btn
                                            v-if="hasPermission('delete')"
                                            icon="mdi-delete-outline"
                                            color="negative"
                                            title="Изтриване на реда"
                                            dense
                                            flat
                                            rounded
                                            @click="confirm(props.row.id)"
                                        />
                                    </q-td>
                                </template>
                                <template v-slot:bottom-row>
                                    <q-tr>
                                        <q-td
                                            colspan="6"
                                            class="text-weight-bold"
                                        >Общо:</q-td>
                                        <q-td class="text-weight-bold">
                                            {{ totalPrice }}
                                        </q-td>
                                        <q-td></q-td>
                                    </q-tr>
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
                        title="Записва направените промени в доставката."
                        icon="mdi-content-save-outline"
                        color="primary"
                    />

                    <q-btn
                        @click.prevent="router.get(route('subdeliveries.create'), {
                            delivery_id: delivery.id,
                        })"
                        label="Добави продукт"
                        title="Добавя нов продукт към доставката."
                        icon="mdi-table-row-plus-after"
                        color="secondary"
                    />

                    <q-btn
                        @click.prevent="confirmCompletion"
                        label="Приключи"
                        title="Приключва доставката. Вписва всички количества продукти. Обновява цените на продуктите."
                        icon="mdi-file-document-check-outline"
                        color="secondary"
                    />

                    <q-btn
                        @click.prevent="confirmDelivery"
                        label="Изтрий"
                        title="Изтрива доставката. Операцията е необратима."
                        icon="mdi-delete-outline"
                        color="negative"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>