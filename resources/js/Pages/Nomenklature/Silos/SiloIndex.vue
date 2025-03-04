<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { nextTick, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    silos: {
        type: Object,
        required: true,
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
        style: 'width: 80px;',
        sortable: true,
    },
    {
        name: 'factory_id',
        align: 'left',
        label: 'База',
        field: row => row.factory.name,
        style: 'width: 200px;',
        sortable: true,
    },
    {
        name: 'name',
        align: 'left',
        label: 'Име',
        field: 'name',
        style: 'width: 140px;',
        sortable: true,
    },
    {
        name: 'halls',
        align: 'left',
        label: 'Халета',
        field: 'halls',
        sortable: false,
    },
    {
        name: 'maxqt',
        align: 'left',
        label: 'Макс. [кг.]',
        field: 'maxqt',
        style: 'width: 80px;',
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
        name: 'stock',
        align: 'left',
        label: 'Налично [кг.]',
        field: 'stock',
        style: 'width: 80px;',
        sortable: true,
    },
    {
        name: 'price',
        align: 'left',
        label: 'Ед. цена',
        field: 'price',
        style: 'width: 80px;',
        sortable: false,
    },
    {
        name: "actions",
        label: "Управление",
        align: "center",
        field: "actions",
    }
]

const pagination = ref({
    rowsPerPage: 0
})
const filter = ref(props.filter)
const loading = ref(false)
const pageSize = ref(props.silos.meta.per_page)
const lastPage = ref(props.silos.meta.last_page)
const nextPage = ref(props.silos.meta.current_page + 1)

const fieldHalls = (row) => {
    var hallbetween = ''
    const mhalls = row.mhalls?.map(obj => obj.name).join(', ')
    if (mhalls.length > 0) {
        hallbetween = ', '
    }
    const uhalls = row.uhalls?.map(obj => obj.name).join(', ')
    return mhalls + hallbetween + uhalls
}

const title = 'Силози'
const { hasPermission } = usePermission()
const $q = useQuasar()

const onScroll = ({ to, index, ref }) => {
    if (index > 0) {
        const lastIndex = props.silos.data.length - 1
        if (loading.value !== true && nextPage.value < lastPage.value && to === lastIndex) {
            loading.value = true
            setTimeout(() => {
                nextPage.value++
                nextTick(() => {
                    console.log('stores.index')
                    ref.refresh()
                    loading.value = false
                })
            }, 500)
        }
    }



    return
    router.get(
        route('stores.index'),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: requestProp.pagination.sortBy,
            sortOrder: requestProp.pagination.descending ? 'desc' : 'asc',
            filter: filter.value,
        },
        {
            preserveState: false,
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

const confirm = (silo_id) => {
    $q.dialog({
        title: 'Потвърди',
        message: 'Желаеш ли да изтриеш силоза?',
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
        router.delete(route('silos.destroy', silo_id), {
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
                            style="height: 400px"
                            flat
                            bordered
                            :title="title"
                            :rows="silos.data"
                            :columns="columns"
                            row-key="id"
                            :loading="loading"
                            virtual-scroll
                            :virtual-scroll-item-size="48"
                            :virtual-scroll-sticky-size-start="48"
                            :pagination="pagination"
                            :rows-per-page-options="[0]"
                            @virtual-scroll="onScroll"
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
                                    style="width:120px;"
                                >
                                    <q-btn
                                        v-if="hasPermission('update')"
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        title="Промяна на силоз"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('silos.edit', props.row.id))"
                                    />
                                    <q-btn
                                        v-if="hasPermission('delete')"
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        title="Изтриване на силоз"
                                        dense
                                        flat
                                        rounded
                                        @click="confirm(props.row.id)"
                                    />
                                </q-td>
                            </template>
                            <template v-slot:body-cell-halls="props">
                                <q-td
                                    :props="props"
                                    class="text-wrap"
                                >
                                    {{ fieldHalls(props.row) }}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-product="props">
                                <q-td :props="props">
                                    {{ props.row.product ? `[${props.row.product.nomenklature}]
                                    ${props.row.product?.name}` : '' }}
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
                        @click="router.get(route('dashboard'))"
                    />

                    <q-btn
                        v-if="hasPermission('create')"
                        color="primary"
                        label="Нов силоз"
                        icon="mdi-file-document-plus-outline"
                        @click="router.get(route('silos.create'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>

<style scoped>
.text-wrap {
    white-space: normal;
    word-wrap: break-word;
}
</style>
