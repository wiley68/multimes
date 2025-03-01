<script setup>
import { usePermission } from '@/composables/permissions'
import { useQuasar } from 'quasar'
import { computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import moment from 'moment'

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    },
    mincrements: {
        type: Array,
        required: true
    },
    mhallInfo: {
        type: Object,
        required: true
    },
})

const { hasPermission } = usePermission()

const incrementsColumns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: 'id',
        sortable: true,
    },
    {
        name: 'product',
        align: 'left',
        label: 'Продукт',
        field: 'product',
        sortable: true,
    },
    {
        name: 'type',
        align: 'left',
        label: 'Тип',
        field: 'type',
        sortable: true,
    },
    {
        name: 'created_at',
        align: 'left',
        label: 'Създаден на',
        field: 'created_at',
        sortable: true,
    },
    {
        name: 'quantity',
        align: 'left',
        label: 'Количество',
        field: 'quantity',
        sortable: true,
    },
    {
        name: 'me',
        align: 'left',
        label: 'м.е.',
        field: 'me',
        sortable: false,
    },
    {
        name: 'price',
        align: 'left',
        label: 'Цена [лв]',
        field: 'price',
        sortable: true,
    },
    {
        name: 'allprice',
        align: 'left',
        label: 'Общо [лв]',
        field: 'allprice',
        sortable: true,
    },
    {
        name: 'allweight',
        align: 'left',
        label: 'Общо [кг]',
        field: 'allweight',
        sortable: true,
    },
    {
        name: 'status',
        align: 'left',
        label: 'Състояние',
        field: 'status',
        sortable: true,
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const totalPrice = computed(() => {
    return props.mincrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
})

const $q = useQuasar()

const mincrementsCreate = (type) => {
    router.get(
        route('mincrements.create'),
        {
            mproduction_id: props.mproduction.id,
            type: type,
        },
        {
            onError: errors => {
                Object.values(errors).flat().forEach((error) => {
                    $q.notify({
                        message: error,
                        icon: 'mdi-alert-circle-outline',
                        type: 'negative',
                    });
                });
            },
        }
    )
}

const confirm = (increments_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш прихода?',
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
        router.delete(route('mincrements.destroy', increments_id), {
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

const confirmCompletion = (mincrement) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да приключиш този приход? Количеството от избрания продукт ще бъде намалено в халето за текущия процес, като ще промени текущите наличности. Процеса е необратим!',
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
        const form = useForm({
            mproduction_id: mincrement.mproduction_id,
            product: mincrement.product,
            quantity: mincrement.quantity,
            weight: mincrement.weight,
            price: mincrement.price,
            status: mincrement.status,
            type: mincrement.type,
        })
        form.patch(route('mincrements.complete', mincrement.id), {
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

const confirmCompletionPodrastvane = (mincrement) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Какво количество Прасета подрастване са родени?',
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
        prompt: {
            model: mincrement.quantity,
            type: 'number',
            min: 1,
            max: 1000,
            step: 1
        },
    }).onOk((data) => {
        const form = useForm({
            mproduction_id: mincrement.mproduction_id,
            product: mincrement.product,
            quantity: mincrement.quantity,
            weight: mincrement.weight,
            price: mincrement.price,
            status: mincrement.status,
            type: mincrement.type,
            podrastvane: data,
            podrastvane_price: (mincrement.quantity * mincrement.price) / data,
        })
        form.patch(route('mincrements.complete', mincrement.id), {
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
                :rows="mincrements"
                :columns="incrementsColumns"
                row-key="id"
                :rows-per-page-options=[7]
            >
                <template v-slot:body="props">
                    <q-tr
                        :props="props"
                        :class="props.row.status === 1 ? 'bg-red-3' : ''"
                    >
                        <q-td
                            v-for="col in props.cols"
                            :key="col.name"
                            :props="props"
                        >
                            <div
                                v-if="col.name === 'id'"
                                style="width: 40px;"
                            >
                                {{ props.row.id }}
                            </div>
                            <div v-else-if="col.name === 'product'">
                                {{ props.row.product ? `[${props.row.product.nomenklature}] ${props.row.product.name}` :
                                    ''
                                }}
                            </div>
                            <div
                                v-else-if="col.name === 'type'"
                                style="width: 60px;"
                            >
                                {{ props.row.type }}
                            </div>
                            <div
                                v-else-if="col.name === 'created_at'"
                                style="width: 60px;"
                            >
                                {{ moment(props.row.created_at).format('DD.MM.YY') }}
                            </div>
                            <div
                                v-else-if="col.name === 'quantity'"
                                style="width: 40px;"
                            >
                                {{ props.row.quantity }}
                            </div>
                            <div
                                v-else-if="col.name === 'me'"
                                style="width: 40px;"
                            >
                                {{ props.row.product.me }}
                            </div>
                            <div
                                v-else-if="col.name === 'price'"
                                style="width: 40px;"
                            >
                                {{ parseFloat(props.row.price).toFixed(2) }}
                            </div>
                            <div
                                v-else-if="col.name === 'allprice'"
                                style="width: 60px;"
                            >
                                {{ parseFloat(props.row.price * props.row.quantity).toFixed(2) }}
                            </div>
                            <div
                                v-else-if="col.name === 'allweight'"
                                style="width: 60px;"
                            >
                                {{ parseFloat(props.row.weight).toFixed(2) }}
                            </div>
                            <div
                                v-else-if="col.name === 'status'"
                                style="width: 60px;"
                            >
                                {{ props.row['status'] === 0 ? 'Типов' : 'Приключен' }}
                            </div>
                            <div
                                v-else="col.name === 'actions'"
                                style="width: 60px;"
                            >
                                <template v-if="mproduction.mhall.type === 'Родилно'">
                                    <q-btn
                                        v-if="hasPermission('update') && props.row.status === 0 && mproduction.status === 1"
                                        icon="mdi-file-document-check-outline"
                                        color="primary"
                                        title="Приключване на прихода"
                                        dense
                                        flat
                                        rounded
                                        @click="confirmCompletionPodrastvane(
                                            {
                                                id: props.row.id,
                                                mproduction_id: props.row.mproduction?.id,
                                                product: props.row.product,
                                                quantity: props.row.quantity,
                                                weight: props.row.weight,
                                                price: props.row.price,
                                                status: 1,
                                                type: props.row.type,
                                            }
                                        )"
                                    />
                                </template>
                                <template v-else>
                                    <q-btn
                                        v-if="hasPermission('update') && props.row.status === 0 && mproduction.status === 1"
                                        icon="mdi-file-document-check-outline"
                                        color="primary"
                                        title="Приключване на прихода"
                                        dense
                                        flat
                                        rounded
                                        @click="confirmCompletion(
                                            {
                                                id: props.row.id,
                                                mproduction_id: props.row.mproduction?.id,
                                                product: props.row.product,
                                                quantity: props.row.quantity,
                                                weight: props.row.weight,
                                                price: props.row.price,
                                                status: 1,
                                                type: props.row.type,
                                            }
                                        )"
                                    />
                                </template>
                                <q-btn
                                    v-if="hasPermission('update') && props.row.status === 0 && mproduction.status === 1"
                                    icon="mdi-pencil-outline"
                                    color="primary"
                                    title="Промяна на прихода"
                                    dense
                                    flat
                                    rounded
                                    @click="router.get(route('mincrements.edit', props.row.id))"
                                />
                                <q-btn
                                    v-if="hasPermission('delete') && props.row.status === 0 && mproduction.status === 1"
                                    icon="mdi-delete-outline"
                                    color="negative"
                                    title="Изтриване на прихода"
                                    dense
                                    flat
                                    rounded
                                    @click="confirm(props.row.id)"
                                />
                            </div>
                        </q-td>
                    </q-tr>
                </template>
                <template v-slot:bottom-row>
                    <q-tr>
                        <q-td
                            colspan="7"
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
                v-if="mproduction.status === 1"
                @click="mincrementsCreate('Продажба')"
                label="Продажба на прасета"
                title="Извършване на продажба на прасета. Продадените прасета се премахват от процеса. В процеса се вписва приход на средства."
                icon="mdi-table-row-plus-after"
                color="primary"
            />
            <q-btn
                v-if="mproduction.status === 1"
                @click="mincrementsCreate('Прехвърляне')"
                :label="mhallInfo.nextproduct"
                :title="`Добавя нов приход към продукционния процес. Прихода е от предаване на ${mhallInfo.product} готови за ${mhallInfo.nextproduct}.`"
                icon="mdi-table-row-plus-after"
                color="primary"
            />
            <q-btn
                v-if="mproduction.status === 1"
                @click="mincrementsCreate('Умрели')"
                label="Прасета умрели"
                title="Извършва се премахване на умрели прасета от процеса."
                icon="mdi-table-row-plus-after"
                color="primary"
            />
        </div>
    </div>
</template>
