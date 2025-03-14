<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed } from 'vue'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'

const props = defineProps({
  mproduction: {
    type: Object,
    required: true,
  },
  mdecrements: {
    type: Array,
    required: true,
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
    style: 'width: 40px;',
  },
  {
    name: 'product',
    align: 'left',
    label: 'Продукт',
    field: 'product',
    sortable: true,
  },
  {
    name: 'created_at',
    align: 'left',
    label: 'Създаден на',
    field: 'created_at',
    sortable: true,
    style: 'width: 60px;',
  },
  {
    name: 'quantity',
    align: 'left',
    label: 'Количество',
    field: 'quantity',
    sortable: true,
    style: 'width: 60px;',
  },
  {
    name: 'me',
    align: 'left',
    label: 'м.е.',
    field: 'me',
    sortable: false,
    style: 'width: 40px;',
  },
  {
    name: 'price',
    align: 'left',
    label: 'Цена [лв]',
    field: 'price',
    sortable: true,
    style: 'width: 80px;',
  },
  {
    name: 'allprice',
    align: 'left',
    label: 'Общо [лв]',
    field: 'allprice',
    sortable: true,
    style: 'width: 120px;',
  },
  {
    name: 'status',
    align: 'left',
    label: 'Състояние',
    field: 'status',
    sortable: true,
    style: 'width: 80px;',
  },
  {
    name: 'actions',
    label: 'Управление',
    align: 'center',
    field: 'actions',
    style: 'width: 80px;',
  },
]

const totalPrice = computed(() => {
  return props.mdecrements
    .filter((item) => item.status === 1)
    .reduce((total, item) => total + item.quantity * item.price, 0)
    .toFixed(2)
})

const $q = useQuasar()

const createMdecrements = () => {
  router.get(
    route('mdecrements.create'),
    { mproduction_id: props.mproduction.id },
    {
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

const mdecrementsEdit = (decrements_id) => {
  router.get(route('mdecrements.edit', decrements_id), {
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
      flat: true,
    },
  })
    .onOk(() => {
      router.delete(route('mdecrements.destroy', decrements_id), {
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

const confirmCompletion = (mdecrement) => {
  $q.dialog({
    title: 'Потвърди',
    message:
      'Желаеш ли да приключиш този разход? Количеството от избрания продукт ще бъде намалено в склада, като ще промени текущите наличности. Процеса е необратим!',
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
      const form = useForm({
        mproduction_id: mdecrement.mproduction_id,
        product: mdecrement.product,
        quantity: mdecrement.quantity,
        weight: 0.0,
        price: mdecrement.price,
        status: mdecrement.status,
      })
      form.patch(route('mdecrements.complete', mdecrement.id), {
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
  <div class="column full-height full-width">
    <div
      class="col scrollable-content"
      style="border-bottom: 1px solid #e0e0e0"
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
        :rows="mdecrements"
        :columns="decrementsColumns"
        row-key="id"
        :rows-per-page-options="[7]"
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
              <div v-if="col.name === 'id'">
                {{ props.row.id }}
              </div>
              <div v-else-if="col.name === 'product'">
                {{
                  props.row.product
                    ? `[${props.row.product.nomenklature}] ${props.row.product.name}`
                    : ''
                }}
              </div>
              <div v-else-if="col.name === 'created_at'">
                {{ moment(props.row.created_at).format('DD.MM.YY') }}
              </div>
              <div v-else-if="col.name === 'quantity'">
                {{ props.row.quantity }}
              </div>
              <div v-else-if="col.name === 'me'">
                {{ props.row.product.me }}
              </div>
              <div v-else-if="col.name === 'price'">
                {{ parseFloat(props.row.price).toFixed(2) }}
              </div>
              <div v-else-if="col.name === 'allprice'">
                {{
                  parseFloat(props.row.price * props.row.quantity).toFixed(2)
                }}
              </div>
              <div v-else-if="col.name === 'status'">
                {{ props.row['status'] === 0 ? 'Типов' : 'Приключен' }}
              </div>
              <div v-else="col.name === 'actions'">
                <q-btn
                  v-if="
                    hasPermission('update') &&
                    props.row.status === 0 &&
                    mproduction.status === 1
                  "
                  icon="mdi-file-document-check-outline"
                  color="primary"
                  title="Приключване на разхода"
                  dense
                  flat
                  rounded
                  @click="
                    confirmCompletion({
                      id: props.row.id,
                      mproduction_id: props.row.mproduction?.id,
                      product: {
                        value: props.row.product?.id,
                        label: props.row.product?.name,
                      },
                      quantity: props.row.quantity,
                      price: props.row.price,
                      status: 1,
                    })
                  "
                />
                <q-btn
                  v-if="
                    hasPermission('update') &&
                    props.row.status === 0 &&
                    mproduction.status === 1
                  "
                  icon="mdi-pencil-outline"
                  color="primary"
                  title="Промяна на разхода"
                  dense
                  flat
                  rounded
                  @click="mdecrementsEdit(props.row.id)"
                />
                <q-btn
                  v-if="
                    hasPermission('delete') &&
                    props.row.status === 0 &&
                    mproduction.status === 1
                  "
                  icon="mdi-delete-outline"
                  color="negative"
                  title="Изтриване на разхода"
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
              colspan="6"
              class="text-weight-bold"
              >Общо:</q-td
            >
            <q-td class="text-weight-bold">
              {{ totalPrice }}
            </q-td>
            <q-td></q-td>
          </q-tr>
        </template>
      </q-table>
    </div>
    <div
      style="height: 48px"
      class="row items-center q-gutter-x-sm q-px-sm"
    >
      <q-btn
        v-if="hasPermission('create') && mproduction.status === 1"
        @click="createMdecrements"
        label="Добави разход"
        title="Добавя нов разход към продукционния процес."
        icon="mdi-table-row-plus-after"
        color="primary"
      />
    </div>
  </div>
</template>
