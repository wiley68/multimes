<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
  uproduction: {
    type: Object,
    required: true,
  },
})

const { hasPermission } = usePermission()

const $q = useQuasar()
const confirmCompletion = () => {
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
      form.patch(route('uproductions.complete', props.uproduction.id), {
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
    route('silos.loading', {
      silo: props.uproduction.uhall.silo.id,
      uproduction: props.uproduction.id,
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

const uproductionIndex = () => {
  router.get(route('uproductions.index'), {
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

const uproductionLoading = () => {
  router.get(
    route('uproductions.loading', { uproduction: props.uproduction.id }),
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
          Производствен Процес №{{ uproduction.id }} -
          {{ uproduction.group_number }}/{{ uproduction.partida_number }}
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <div class="text-h5">
          [{{ uproduction.product?.nomenklature }}]
          {{ uproduction.product?.name }}
        </div>
        <div class="text-caption">{{ uproduction.product?.description }}</div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          Група №: {{ uproduction.group_number }}
        </div>
        <div class="text-subtitle1">
          Партида №: {{ uproduction.partida_number }}
        </div>
        <div class="text-subtitle1">
          Налично в склад: {{ uproduction.product?.stock }}
          {{ uproduction.product?.me }}
        </div>
        <div class="text-subtitle1">
          Текущ брой прасета: {{ uproduction.stock }}
          {{ uproduction.product?.me }}
        </div>
        <div class="text-subtitle1">
          Състояние: {{ uproduction.status === 1 ? 'Активен' : 'Приключен' }}
        </div>
      </q-card-section>
      <q-card-actions>
        <q-btn
          color="primary"
          label="Процеси угояване"
          flat
          icon="mdi-menu-left"
          style="padding: 0px 15px"
          @click="uproductionIndex"
        />
        <q-btn
          v-if="hasPermission('update') && uproduction.status === 1"
          label="Зареди прасета"
          :title="`Зарежда 'Прасета угояване' в продукционен процес №${uproduction.id}, Хале: ${uproduction.uhall.name}.`"
          color="primary"
          icon="mdi-upload-multiple-outline"
          style="padding: 0px 15px"
          @click.prevent="uproductionLoading"
        />
        <q-btn
          v-if="hasPermission('update') && uproduction.status === 1"
          @click.prevent="confirmCompletion"
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
        <div class="text-h6 text-center">{{ uproduction.uhall.silo.name }}</div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <div class="text-h5">
          [{{ uproduction.uhall.silo.product?.nomenklature }}]
          {{ uproduction.uhall.silo.product?.name }}
        </div>
        <div class="text-caption">
          {{ uproduction.uhall.silo.product?.description }}
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          Налично в склад:
          {{ uproduction.uhall.silo.product?.stock.toFixed(2) }}
          {{ uproduction.uhall.silo.product?.me }}
        </div>
        <div class="text-subtitle1">
          Максимум: {{ parseFloat(uproduction.uhall.silo.maxqt).toFixed(2) }}
          {{ uproduction.uhall.silo.product?.me }}
        </div>
        <div class="text-subtitle1">
          Налично в силоза:
          {{ parseFloat(uproduction.uhall.silo.stock).toFixed(2) }}
          {{ uproduction.uhall.silo.product?.me }}
        </div>
      </q-card-section>
      <q-card-actions vertical>
        <q-btn
          v-if="hasPermission('update') && uproduction.status === 1"
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
