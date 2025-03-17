<script setup>
import { computed } from 'vue'
import moment from 'moment'
import dayjs from 'dayjs'
import weekOfYear from 'dayjs/plugin/weekOfYear'
import { ref } from 'vue'

const props = defineProps({
  mproduction: {
    type: Object,
    required: true,
  },
  mdecrements: {
    type: Array,
    required: true,
  },
  mincrements: {
    type: Array,
    required: true,
  },
  mhallInfo: {
    type: Object,
    required: true,
  },
})

dayjs.extend(weekOfYear)
const currentWeek = ref(dayjs().week())

const getDaysBetweenTodayAndDate = (targetDate) => {
  const today = moment().startOf('day')
  const target = moment(targetDate).startOf('day')
  return today.diff(target, 'days')
}

const getDaysBetweenCreateAndFinished = (createDate, finishedDate) => {
  const create = moment(createDate).startOf('day')
  const finished = moment(finishedDate).startOf('day')
  return create.diff(finished, 'days')
}

const siloPurcent = computed(() => {
  return parseFloat(
    (
      parseFloat(props.mproduction.mhall.silo.stock) /
      parseFloat(props.mproduction.mhall.silo.maxqt)
    ).toFixed(2)
  )
})
const siloPurcentLabel = `${(siloPurcent.value * 100).toFixed(2)} %`

const siloLoadingsCount = computed(() => {
  return props.mdecrements.filter(
    (item) => item.product.type === props.mhallInfo.furaz
  ).length
})

const siloLoadingsWeight = computed(() => {
  return props.mdecrements.reduce(
    (acc, item) =>
      item.product.type === props.mhallInfo.furaz ? acc + item.quantity : acc,
    0
  )
})

const productionDecrementsCount = computed(() => {
  return props.mdecrements.filter(
    (item) => item.product.type === props.mhallInfo.product
  ).length
})

const productionDecrementsQuantity = computed(() => {
  return props.mdecrements.reduce(
    (acc, item) =>
      item.product.type === props.mhallInfo.product ? acc + item.quantity : acc,
    0
  )
})

const productionDecrementsWeight = computed(() => {
  return props.mdecrements.reduce(
    (acc, item) =>
      item.product.type === props.mhallInfo.product ? acc + item.weight : acc,
    0
  )
})

const productionIncrementsCount = computed(() => {
  return props.mincrements.filter(
    (item) => item.product.type === props.mhallInfo.product
  ).length
})

const productionIncrementsQuantity = computed(() => {
  return props.mincrements.reduce(
    (acc, item) =>
      item.status === 1 && item.product.type === props.mhallInfo.product
        ? acc + item.quantity
        : acc,
    0
  )
})

const productionIncrementsWeight = computed(() => {
  return props.mincrements.reduce(
    (acc, item) =>
      item.status === 1 && item.product.type === props.mhallInfo.product
        ? acc + item.weight
        : acc,
    0
  )
})
</script>

<template>
  <div class="col text-h4 q-mr-md">
    <q-card class="my-card full-height column">
      <q-card-section class="bg-primary text-white">
        <div class="text-h6 text-center">Хале</div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          <span class="text-weight-medium">Населено място</span
          ><span class="text-weight-light"
            >: {{ mproduction.mhall.factory.city.name }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Производствена база</span
          ><span class="text-weight-light"
            >: {{ mproduction.mhall.factory.name }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Хале</span
          ><span class="text-weight-light">: {{ mproduction.mhall.name }}</span>
        </div>
      </q-card-section>
      <q-card-actions vertical> </q-card-actions>
    </q-card>
  </div>
  <div class="col text-h4 q-mr-md">
    <q-card class="my-card full-height column">
      <q-card-section class="bg-deep-orange text-white">
        <div class="text-h6 text-center">Силоз</div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          <span class="text-weight-medium">Силоз</span
          ><span class="text-weight-light"
            >: {{ mproduction.mhall.silo.name }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Максимум</span
          ><span class="text-weight-light"
            >: {{ parseFloat(mproduction.mhall.silo.maxqt).toFixed(2) }}
            {{ mproduction.mhall.silo.product?.me }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Продукт</span
          ><span class="text-weight-light"
            >:
            {{
              mproduction.mhall.silo.product
                ? `[${mproduction.mhall.silo.product.nomenklature}]
                        ${mproduction.mhall.silo.product.name}`
                : ''
            }}</span
          >
        </div>
        <div
          v-if="mproduction.mhall.silo.product"
          class="text-subtitle2"
        >
          <span class="text-weight-light">{{
            mproduction.mhall.silo.product.description
          }}</span>
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Наличност</span
          ><span class="text-weight-light"
            >: {{ parseFloat(mproduction.mhall.silo.stock).toFixed(2) }}
            {{ mproduction.mhall.silo.product?.me }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Цена</span
          ><span class="text-weight-light"
            >:
            {{ parseFloat(mproduction.mhall.silo.price).toFixed(2) }} лв.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Брой зареждания на силоза</span
          ><span class="text-weight-light">: {{ siloLoadingsCount }} бр.</span>
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Общо количество зареден фураж</span
          ><span class="text-weight-light">: {{ siloLoadingsWeight }} кг.</span>
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Процент запълване</span
          ><span class="text-weight-light">: {{ siloPurcentLabel }}</span>
        </div>
      </q-card-section>
      <q-card-actions vertical> </q-card-actions>
    </q-card>
  </div>
  <div class="col text-h4 q-mr-md">
    <q-card class="my-card full-height column">
      <q-card-section class="bg-secondary text-white">
        <div class="text-h6 text-center">Производствен Процес</div>
      </q-card-section>
      <q-separator />
      <q-card-section class="col">
        <div class="text-subtitle1">
          <span class="text-weight-medium">Производствен процес №</span
          ><span class="text-weight-light">: {{ mproduction.id }}</span>
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Група №</span
          ><span class="text-weight-light"
            >: {{ mproduction.group_number }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Партида №</span
          ><span class="text-weight-light"
            >: {{ mproduction.partida_number }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Стартиран на</span
          ><span class="text-weight-light"
            >:
            {{ moment(mproduction.created_at).format('DD.MM.YY HH:mm') }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Състояние</span
          ><span class="text-weight-light"
            >: {{ mproduction.status === 1 ? 'Активен' : 'Приключен' }}</span
          >
        </div>
        <div
          v-if="mproduction.status === 0"
          class="text-subtitle1"
        >
          <span class="text-weight-medium">Приключен на</span
          ><span class="text-weight-light"
            >:
            {{ moment(mproduction.finished_at).format('DD.MM.YY HH:mm') }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Седмица номер</span
          ><span class="text-weight-light">: {{ currentWeek }}</span>
        </div>
        <div
          v-if="mproduction.product"
          class="text-subtitle1"
        >
          <span class="text-weight-medium">Продукт</span
          ><span class="text-weight-light"
            >:
            {{
              `[${mproduction.product.nomenklature}] ${mproduction.product.name}`
            }}</span
          >
        </div>
        <div
          v-if="mproduction.product"
          class="text-subtitle2 text-weight-light"
        >
          {{ mproduction.product.description }}
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Текущ брой прасета</span
          ><span class="text-weight-light"
            >: {{ mproduction.stock }} {{ mproduction.product?.me }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Текуща цена</span
          ><span class="text-weight-light">: {{ mproduction.price }} лв.</span>
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Брой вкарвания на прасета</span
          ><span class="text-weight-light"
            >: {{ productionDecrementsCount }} бр.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Общ брой вкарани прасета</span
          ><span class="text-weight-light"
            >: {{ productionDecrementsQuantity }} бр.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Общо тегло вкарани прасета</span
          ><span class="text-weight-light"
            >: {{ productionDecrementsWeight }} кг.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Брой изкарвания на прасета</span
          ><span class="text-weight-light"
            >: {{ productionIncrementsCount }} бр.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Общ брой изкарани прасета</span
          ><span class="text-weight-light"
            >: {{ productionIncrementsQuantity }} бр.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Общо тегло изкарани прасета</span
          ><span class="text-weight-light"
            >: {{ productionIncrementsWeight }} кг.</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Съотношение Фураж/1кг. тегло</span
          ><span class="text-weight-light"
            >:
            {{
              productionIncrementsWeight - productionDecrementsWeight !== 0
                ? (
                    siloLoadingsWeight /
                    (productionIncrementsWeight - productionDecrementsWeight)
                  ).toFixed(2)
                : 0
            }}</span
          >
        </div>
        <div class="text-subtitle1">
          <span class="text-weight-medium">Брой дни в процес</span
          ><span class="text-weight-light"
            >:
            {{
              mproduction.status === 1
                ? getDaysBetweenTodayAndDate(mproduction.created_at)
                : getDaysBetweenCreateAndFinished(
                    mproduction.finished_at,
                    mproduction.created_at
                  )
            }}</span
          >
        </div>
      </q-card-section>
      <q-card-actions vertical> </q-card-actions>
    </q-card>
  </div>
</template>
