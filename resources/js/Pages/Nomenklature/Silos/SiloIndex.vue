<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
    silos: {
        type: Object,
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
        name: 'mhalls',
        align: 'left',
        label: 'Халета',
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

const fieldMhall = (row) => {
    var hallbetween = ''
    const mhalls = row.mhalls.map(obj => obj.name).join(', ')
    if (mhalls.length > 0) {
        hallbetween = ', '
    }
    const uhalls = row.uhalls.map(obj => obj.name).join(', ')
    return mhalls + hallbetween + uhalls
}

const title = 'Силози'
const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = {
    page: props.silos.meta.current_page,
    rowsPerPage: props.silos.meta.per_page,
    rowsNumber: props.silos.meta.total
}
const filter = ref(props.filter)
const navigationActive = ref(false)

const onRequest = (requestProp) => {
    router.get(
        route('silos.index'),
        {
            page: requestProp.pagination.page,
            rowsPerPage: requestProp.pagination.rowsPerPage,
            sortBy: requestProp.pagination.sortBy,
            sortOrder: requestProp.pagination.descending ? 'desc' : 'asc',
            filter: filter.value,
        },
        {
            preserveState: false,
        }
    );
}

const activateNavigation = () => {
    navigationActive.value = true
}

const deactivateNavigation = () => {
    navigationActive.value = false
}

const tableClass = computed(() => navigationActive.value === true ? 'shadow-8 no-outline' : null)

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
                            class="my-sticky-header-table text-wrap-table"
                            :class="tableClass"
                            bordered
                            title="Силози"
                            rows-per-page-label="Записи на страница"
                            separator="cell"
                            no-data-label="Липсват данни"
                            no-results-label="Няма съответстващи записи"
                            loading-label="Данните се зареждат..."
                            table-header-class="bg-grey-3"
                            :rows="silos.data"
                            :columns="columns"
                            row-key="id"
                            :pagination="pagination"
                            :filter="filter"
                            @request="onRequest"
                            @focusin="activateNavigation"
                            @focusout="deactivateNavigation"
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
                                        icon="mdi-upload-multiple-outline"
                                        color="primary"
                                        title="Зареждане на силоз"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('silos.loading', props.row.id))"
                                    />

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
                            <template v-slot:body-cell-mhalls="props">
                                <q-td :props="props">
                                    {{ props.row.product ? `[${props.row.product.nomenklature}]
                                    ${props.row.product?.name}` : '' }}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-product="props">
                                <q-td
                                    :props="props"
                                    class="text-wrap"
                                >
                                    {{ fieldMhall(props.row) }}
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