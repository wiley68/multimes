<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed, onMounted } from 'vue'
import { usePermission } from '@/composables/permissions'


const props = defineProps({
    udecrements: {
        type: Array,
        required: true
    },
})

const { hasPermission } = usePermission()

const decrementsColumns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: 'id',
        sortable: true,
        style: "width: 80px;",
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
    return props.udecrements
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
})

const confirm = (decrements_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш разхода?',
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
        router.delete(route('udecrements.destroy', decrements_id), {
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
    <div class="column full-height full-width">
        <div
            class="col scrollable-content"
            style="border-bottom: 1px solid #E0E0E0;"
        >
            <q-table
                class="my-sticky-header-table full-width"
                bordered
                rows-per-page-label="Записи на страница"
                separator="cell"
                no-data-label="Липсват данни"
                no-results-label="Няма съответстващи записи"
                loading-label="Данните се зареждат..."
                table-header-class="bg-grey-3"
                :rows="udecrements"
                :columns="decrementsColumns"
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
                            colspan="5"
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
        <div
            style="height: 48px;"
            class="row items-center q-gutter-x-sm q-px-sm"
        >
            <q-btn
                label="Добави разход"
                title="Добавя нов разход към продукционния процес."
                icon="mdi-table-row-plus-after"
                color="primary"
            />
        </div>
    </div>
</template>