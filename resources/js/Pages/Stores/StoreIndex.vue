<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { usePermission } from '@/composables/permissions'
import { useQuasar } from 'quasar'

const props = defineProps({
  stores: {
    type: Object,
  },
  filter: {
    type: String,
  },
  sortBy: {
    type: String,
  },
  sortOrder: {
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
    style: 'width: 40px;',
    sortable: false,
  },
  {
    name: 'nomenklature',
    align: 'left',
    label: 'Номенклатура',
    field: 'nomenklature',
    style: 'width: 80px;',
    sortable: false,
  },
  {
    name: 'name',
    align: 'left',
    label: 'Име',
    field: 'name',
    sortable: false,
  },
  {
    name: 'type',
    align: 'left',
    label: 'Предназначение',
    field: 'type',
    style: 'width: 80px;',
    sortable: false,
  },
  {
    name: 'price',
    align: 'left',
    label: 'Цена склад',
    field: 'price',
    style: 'width: 100px;',
    sortable: false,
  },
  {
    name: 'stock',
    align: 'left',
    label: 'Наличност склад',
    field: 'stock',
    style: 'width: 100px;',
    sortable: false,
  },
  {
    name: 'stock_out',
    align: 'left',
    label: 'Наличност обекти',
    field: 'stock_out',
    style: 'width: 100px;',
    sortable: false,
  },
  {
    name: 'total',
    align: 'left',
    label: 'Общо',
    field: 'total',
    style: 'width: 100px; color: #9C27B0; font-weight: bold; font-size: 110%;',
    sortable: false,
  },
  {
    name: 'me',
    align: 'left',
    label: 'м.е.',
    field: 'me',
    style: 'width: 80px;',
    sortable: false,
  },
  {
    name: 'actions',
    label: 'Управление',
    align: 'center',
    field: 'actions',
    style: 'width: 80px;',
    sortable: false,
  },
]

const isSortColumn = (col) => {
  return [
    'id',
    'nomenklature',
    'name',
    'type',
    'price',
    'stock',
    'me',
  ].includes(col)
}

const title = 'Наличности'
const { hasPermission } = usePermission()
const pagination = ref({
  page: props.stores.current_page,
  rowsPerPage: props.stores.per_page,
  rowsNumber: props.stores.total,
  sortBy: props.sortBy,
  sortOrder: props.sortOrder,
})
const filter = ref(props.filter)

const $q = useQuasar()

const storesIndex = (requestProp) => {
  router.get(
    route('stores.index'),
    {
      page: requestProp.pagination.page,
      rowsPerPage: requestProp.pagination.rowsPerPage,
      sortBy: pagination.value.sortBy,
      sortOrder: pagination.value.sortOrder,
      filter: filter.value,
    },
    {
      preserveState: false,
      onError: (errors) => {
        Object.values(errors)
          .flat()
          .forEach((error) => {
            $q.notify({
              message: error,
              icon: 'mdi-alert-circle-outline',
              type: 'negative',
            })
          })
      },
    }
  )
}

const sortByColumn = (col) => {
  if (pagination.value.sortBy === col) {
    pagination.value.sortOrder =
      pagination.value.sortOrder === 'asc' ? 'desc' : 'asc'
  } else {
    pagination.value.sortBy = col
    pagination.value.sortOrder = 'asc'
  }
  storesIndex({ pagination })
}

const storesShow = (row) => {
  router.get(route('stores.show', row.id), {
    onError: (errors) => {
      Object.values(errors)
        .flat()
        .forEach((error) => {
          $q.notify({
            message: error,
            icon: 'mdi-alert-circle-outline',
            type: 'negative',
          })
        })
    },
  })
}

const dashboard = () => {
  router.get(route('dashboard'), {
    onError: (errors) => {
      Object.values(errors)
        .flat()
        .forEach((error) => {
          $q.notify({
            message: error,
            icon: 'mdi-alert-circle-outline',
            type: 'negative',
          })
        })
    },
  })
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
              class="my-sticky-header-table"
              bordered
              :title="title"
              rows-per-page-label="Записи на страница"
              separator="cell"
              no-data-label="Липсват данни"
              no-results-label="Няма съответстващи записи"
              loading-label="Данните се зареждат..."
              table-header-class="bg-grey-3"
              :rows="stores.data"
              :columns="columns"
              row-key="id"
              :pagination="pagination"
              :filter="filter"
              @request="storesIndex"
            >
              <template v-slot:header-cell="props">
                <q-th
                  :props="props"
                  :class="
                    isSortColumn(props.col.name)
                      ? 'cursor-pointer text-center'
                      : 'text-center'
                  "
                  @click="
                    isSortColumn(props.col.name)
                      ? sortByColumn(props.col.name)
                      : null
                  "
                >
                  {{ props.col.label }}&nbsp;
                  <q-icon
                    v-if="isSortColumn(props.col.name)"
                    :name="
                      props.col.name === pagination.sortBy &&
                      pagination.sortOrder === 'desc'
                        ? 'mdi-sort-ascending'
                        : 'mdi-sort-descending'
                    "
                  />
                </q-th>
              </template>
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td
                    v-for="col in props.cols"
                    :key="col.name"
                    :props="props"
                  >
                    <div v-if="col.name === 'actions'">
                      <q-btn
                        v-if="hasPermission('view')"
                        icon="mdi-table-eye"
                        color="primary"
                        title="Преглед на наличността"
                        dense
                        flat
                        rounded
                        @click="storesShow(props.row)"
                      />
                    </div>
                    <div v-else-if="col.name === 'price'">
                      {{ props.row.price }}
                    </div>
                    <div v-else-if="col.name === 'stock'">
                      {{ props.row.stock.toFixed(2) }}
                    </div>
                    <div v-else-if="col.name === 'stock_out'">
                      {{
                        (
                          props.row.silos.reduce((sum, item) => {
                            return sum + parseFloat(item.stock)
                          }, 0) +
                          props.row.uproductions.reduce((sum, item) => {
                            return sum + parseFloat(item.stock)
                          }, 0) +
                          props.row.mproductions.reduce((sum, item) => {
                            return sum + parseFloat(item.stock)
                          }, 0)
                        ).toFixed(2)
                      }}
                    </div>
                    <div v-else-if="col.name === 'total'">
                      {{
                        (
                          props.row.stock +
                          props.row.silos.reduce((sum, item) => {
                            return sum + parseFloat(item.stock)
                          }, 0) +
                          props.row.uproductions.reduce((sum, item) => {
                            return sum + parseFloat(item.stock)
                          }, 0) +
                          props.row.mproductions.reduce((sum, item) => {
                            return sum + parseFloat(item.stock)
                          }, 0)
                        ).toFixed(2)
                      }}
                    </div>
                    <div v-else>
                      {{ props.row[col.name] }}
                    </div>
                  </q-td>
                </q-tr>
              </template>
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
            </q-table>
          </div>
        </div>
        <div class="footer-panel">
          <q-btn
            color="primary"
            label="Табло"
            flat
            icon="mdi-menu-left"
            @click="dashboard"
          />
        </div>
      </div>
    </q-page>
  </DefaultLayout>
</template>
