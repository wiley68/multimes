<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'

const props = defineProps({
  mproductions: {
    type: Object,
  },
  filter: {
    type: String,
  },
  mhall: {
    type: String,
  },
  mhall_name: {
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
    style: 'width:60px;',
    sortable: false,
    sortMethod: (a, b) => (a < b ? -1 : a > b ? 1 : 0),
  },
  {
    name: 'mhall_id',
    align: 'left',
    label: 'Хале',
    field: 'mhall_id',
    sortable: false,
  },
  {
    name: 'group_number',
    align: 'left',
    label: 'Група',
    field: 'group_number',
    sortable: false,
  },
  {
    name: 'partida_number',
    align: 'left',
    label: 'Партида',
    field: 'partida_number',
    sortable: false,
  },
  {
    name: 'status',
    align: 'left',
    label: 'Състояние',
    field: 'status',
    sortable: false,
  },
  {
    name: 'created_at',
    align: 'left',
    label: 'Стариран на',
    field: 'created_at',
    sortable: false,
  },
  {
    name: 'finished_at',
    align: 'left',
    label: 'Приключен на',
    field: 'finished_at',
    sortable: false,
  },
  {
    name: 'product',
    align: 'left',
    label: 'Прасета',
    field: 'product',
    sortable: false,
  },
  {
    name: 'stock',
    align: 'left',
    label: 'Количество [бр]',
    field: 'stock',
    sortable: false,
  },
  {
    name: 'price',
    align: 'left',
    label: 'Цена',
    field: 'price',
    sortable: false,
  },
  {
    name: 'result',
    align: 'left',
    label: 'Резултат',
    field: 'result',
    sortable: false,
  },
  {
    name: 'actions',
    label: 'Управление',
    align: 'center',
    field: 'actions',
    sortable: false,
  },
]

const isSortColumn = (col) => {
  return [
    'id',
    'mhall_id',
    'group_number',
    'partida_number',
    'status',
    'created_at',
    'finished_at',
    'stock',
    'price',
  ].includes(col)
}

const totalResult = computed(() => {
  return props.mproductions.data
    .reduce(
      (total, item) =>
        total +
        item.mincrements.reduce(
          (sum, item) => sum + item.price * item.quantity,
          0
        ) -
        item.mdecrements.reduce(
          (sum, item) => sum + item.price * item.quantity,
          0
        ),
      0
    )
    .toFixed(2)
})

const title = 'Процеси Майки'
const { hasPermission } = usePermission()
const $q = useQuasar()
const pagination = ref({
  page: props.mproductions.meta.current_page,
  rowsPerPage: props.mproductions.meta.per_page,
  rowsNumber: props.mproductions.meta.total,
  sortBy: props.sortBy,
  sortOrder: props.sortOrder,
})

const filter = ref(props.filter)

const mproductionsIndex = (requestProp) => {
  router.get(
    route('mproductions.index'),
    {
      page: requestProp.pagination.page,
      rowsPerPage: requestProp.pagination.rowsPerPage,
      sortBy: pagination.value.sortBy,
      sortOrder: pagination.value.sortOrder,
      filter: filter.value,
      mhall: props.mhall,
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
  mproductionsIndex({ pagination })
}

const mproductionShow = (mproduction) => {
  router.get(route('mproductions.show', mproduction), {
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

const confirm = (mproduction_id) => {
  $q.dialog({
    title: 'Потвърди',
    message:
      'Желаеш ли да изтриеш този процес? Всички данни за процеса ще бъдат унищожени. Този процес на изтриване е необратим!',
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
      flat: true,
    },
  })
    .onOk(() => {
      router.delete(route('mproductions.destroy', mproduction_id), {
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
    })
    .onCancel(() => {})
    .onDismiss(() => {})
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
              :title="
                mhall_name ? `Процеси Майки в Хале: ${mhall_name}` : title
              "
              rows-per-page-label="Записи на страница"
              separator="cell"
              no-data-label="Липсват данни"
              no-results-label="Няма съответстващи записи"
              loading-label="Данните се зареждат..."
              table-header-class="bg-grey-3"
              :rows="mproductions.data"
              :columns="columns"
              row-key="id"
              :pagination="pagination"
              :filter="filter"
              @request="mproductionsIndex"
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
                      v-if="col.name === 'mhall_id'"
                      style="width: 100px"
                      class="multiline-text"
                    >
                      {{ props.row.mhall.name }}
                    </div>
                    <div
                      v-else-if="col.name === 'status'"
                      style="width: 80px"
                    >
                      {{ props.row['status'] === 1 ? 'Активен' : 'Приключен' }}
                    </div>
                    <div
                      v-else-if="col.name === 'created_at'"
                      style="width: 80px"
                    >
                      {{
                        moment(props.row['created_at']).format('DD.MM.YY HH:mm')
                      }}
                    </div>
                    <div
                      v-else-if="col.name === 'finished_at'"
                      style="width: 80px"
                    >
                      {{
                        props.row['finished_at'] === null
                          ? ''
                          : moment(props.row['finished_at']).format(
                              'DD.MM.YY HH:mm'
                            )
                      }}
                    </div>
                    <div
                      v-else-if="col.name === 'product'"
                      class="multiline-text"
                    >
                      {{
                        props.row.product
                          ? `[${props.row.product.nomenklature}]
                                            ${props.row.product.name}`
                          : ''
                      }}
                    </div>
                    <div
                      v-else-if="col.name === 'stock'"
                      style="width: 80px"
                    >
                      {{
                        parseFloat(props.row.stock) === 0 ? '' : props.row.stock
                      }}
                    </div>
                    <div
                      v-else-if="col.name === 'price'"
                      style="width: 80px"
                    >
                      {{
                        parseFloat(props.row.price) === 0 ? '' : props.row.price
                      }}
                    </div>
                    <div
                      v-else-if="col.name === 'result'"
                      style="width: 80px"
                    >
                      {{
                        (
                          props.row.mincrements.reduce(
                            (sum, item) => sum + item.price * item.quantity,
                            0
                          ) -
                          props.row.mdecrements.reduce(
                            (sum, item) => sum + item.price * item.quantity,
                            0
                          )
                        ).toFixed(2)
                      }}
                    </div>
                    <div v-else-if="col.name === 'actions'">
                      <q-btn
                        v-if="hasPermission('view')"
                        title="Управлявай процеса"
                        icon="mdi-file-tree"
                        color="primary"
                        dense
                        flat
                        rounded
                        @click="mproductionShow(props.row.id)"
                      />
                      <q-btn
                        v-if="hasPermission('delete') && props.row.status === 0"
                        title="Изтрий процеса"
                        icon="mdi-delete-outline"
                        color="negative"
                        dense
                        flat
                        rounded
                        @click="confirm(props.row.id)"
                      />
                    </div>
                    <div v-else>
                      {{ props.row[col.name] }}
                    </div>
                  </q-td>
                </q-tr>
              </template>
              <template v-slot:bottom-row>
                <q-tr>
                  <q-td
                    colspan="10"
                    class="text-weight-bold"
                    >Общо:</q-td
                  >
                  <q-td class="text-weight-bold">
                    {{ totalResult }}
                  </q-td>
                </q-tr>
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
        </div>
      </div>
    </q-page>
  </DefaultLayout>
</template>
