<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
  mproduction: {
    type: Object,
    required: true,
  },
  mhallInfo: {
    type: Object,
    required: true,
  },
})

const { hasPermission } = usePermission()
const $q = useQuasar()
const mproductionsComplete = () => {
  $q.dialog({
    title: 'Потвърди',
    message:
      'Желаеш ли да приключиш този Производствен процес? След приключването на процеса няма да могат да бъдат извършвани промени по него. Халето в което се извършва процеса ще бъде освободено за стартиране на нов процес. Процеса е необратим!',
    prompt: {
      model: '0',
      type: 'number',
      label: 'Остатъчно количество фураж в силоза, при приключване на процеса.',
    },
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
    .onOk((data) => {
      const form = useForm({
        status: 0,
        rest: parseFloat(data),
      })
      form.patch(route('mproductions.complete', props.mproduction.id), {
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

const siloLoading = () => {
  router.get(
    route('silos.mloading', {
      silo: props.mproduction.mhall.silo.id,
      mproduction: props.mproduction.id,
    }),
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

const mproductionIndex = () => {
  router.get(route('mproductions.index'), {
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

const mproductionLoading = () => {
  router.get(
    route('mproductions.loading', { mproduction: props.mproduction.id }),
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
</script>

<template>
  <div class="col text-h4 q-mr-md">
    <q-card class="my-card full-height column">
      <q-card-section class="bg-secondary text-white">
        <div class="text-h6 text-center">
          Производствен Процес №{{ mproduction.id }} -
          {{ mproduction.group_number }}/{{ mproduction.partida_number }}
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <div class="text-h5">
          [{{ mproduction.product?.nomenklature }}]
          {{ mproduction.product?.name }}
        </div>
        <div class="text-caption">{{ mproduction.product?.description }}</div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          Група №: {{ mproduction.group_number }}
        </div>
        <div class="text-subtitle1">
          Партида №: {{ mproduction.partida_number }}
        </div>
        <div class="text-subtitle1">
          Налично в склад: {{ mproduction.product?.stock }}
          {{ mproduction.product?.me }}
        </div>
        <div class="text-subtitle1">
          Текущ брой {{ mhallInfo.product }}: {{ mproduction.stock }}
          {{ mproduction.product?.me }}
        </div>
        <div class="text-subtitle1">
          Състояние: {{ mproduction.status === 1 ? 'Активен' : 'Приключен' }}
        </div>
      </q-card-section>
      <q-card-actions>
        <q-btn
          color="primary"
          label="Процеси майки"
          flat
          icon="mdi-menu-left"
          style="padding: 0px 15px"
          @click="mproductionIndex"
        />
        <q-btn
          v-if="hasPermission('update') && mproduction.status === 1"
          :label="`Зареди ${mhallInfo.product}`"
          :title="`Зарежда ${mhallInfo.product} в продукционния процес.`"
          color="primary"
          icon="mdi-upload-multiple-outline"
          style="padding: 0px 15px"
          @click.prevent="mproductionLoading"
        />
        <q-btn
          v-if="hasPermission('update') && mproduction.status === 1"
          @click.prevent="mproductionsComplete"
          label="Приключи процеса"
          title="Приключва процеса. Прекъсва възможността за промяна в данните за този процес."
          icon="mdi-file-document-check-outline"
          style="padding: 0px 15px"
          color="secondary"
        />
      </q-card-actions>
    </q-card>
  </div>
  <div
    class="text-h4"
    style="width: 300px"
  >
    <q-card class="my-card full-height column">
      <q-card-section class="bg-deep-orange text-white">
        <div class="text-h6 text-center">{{ mproduction.mhall.silo.name }}</div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <div class="text-h5">
          [{{ mproduction.mhall.silo.product?.nomenklature }}]
          {{ mproduction.mhall.silo.product?.name }}
        </div>
        <div class="text-caption">
          {{ mproduction.mhall.silo.product?.description }}
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          Налично в склад:
          {{ mproduction.mhall.silo.product?.stock.toFixed(2) }}
          {{ mproduction.mhall.silo.product?.me }}
        </div>
        <div class="text-subtitle1">
          Максимум: {{ parseFloat(mproduction.mhall.silo.maxqt).toFixed(2) }}
          {{ mproduction.mhall.silo.product?.me }}
        </div>
        <div class="text-subtitle1">
          Налично в силоза:
          {{ parseFloat(mproduction.mhall.silo.stock).toFixed(2) }}
          {{ mproduction.mhall.silo.product?.me }}
        </div>
      </q-card-section>
      <q-card-actions vertical>
        <q-btn
          v-if="hasPermission('update') && mproduction.status === 1"
          label="Зареди фураж"
          title="Зарежда фураж в силоза към този продукционен процес."
          color="primary"
          icon="mdi-upload-multiple-outline"
          @click.prevent="siloLoading"
        />
      </q-card-actions>
    </q-card>
  </div>
</template>
