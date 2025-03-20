<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
  mincrement: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  mproduction_id: props.mincrement?.mproduction?.id,
  product: props.mincrement?.product,
  quantity: props.mincrement?.quantity,
  weight: props.mincrement.weight,
  price: props.mincrement?.price,
  status: props.mincrement?.status,
  type: props.mincrement?.type,
})

const $q = useQuasar()
const mincrementsUpdate = () => {
  form.put(route('mincrements.update', props.mincrement.id), {
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

const mproductionsShow = () => {
  router.get(route('mproductions.show', props.mincrement.mproduction?.id))
}

const total = ref(0)
onMounted(() => {
  total.value = props.mincrement?.mproduction?.stock
})

const nextProduct = (productType) => {
  switch (productType) {
    case 'Прасета ремонтни':
      return 'Прасета заплождане'
    case 'Прасета заплождане':
      return 'Прасета условна бременност'
    case 'Прасета условна бременност':
      return 'Прасета бременност'
    case 'Прасета бременност':
      return 'Прасета родилно'
    case 'Прасета родилно':
      return 'Прасета подрастване, Прасета заплождане'
    case 'Прасета подрастване':
      return 'Прасета угояване'
    default:
      return 'Прасета'
  }
}

const nextProduct2 = (productType) => {
  switch (productType) {
    case 'Прасета ремонтни':
      return ''
    case 'Прасета заплождане':
      return ''
    case 'Прасета условна бременност':
      return ''
    case 'Прасета бременност':
      return ''
    case 'Прасета родилно':
      return ''
    case 'Прасета подрастване':
      return 'Прасета ремонтни'
    default:
      return ''
  }
}

const typeTitle = computed(() => {
  switch (props.mincrement.type) {
    case 'Продажба':
      return {
        title: `Продажба [${props.mincrement.product.type}]`,
        button: 'Запиши продажбата',
      }
    case 'Прехвърляне':
      return {
        title: `Прехвърляне [${props.mincrement.product.type} >> ${nextProduct(
          props.mincrement.product.type
        )}]`,
        button: 'Запиши прехвърлянето',
      }
    case 'Ремонтни':
      return {
        title: `Прехвърляне [${props.mincrement.product.type} >> ${nextProduct2(
          props.mincrement.product.type
        )}]`,
        button: 'Запиши прехвърлянето',
      }
    case 'Умрели':
      return {
        title: `Смърт [${props.mincrement.product.type}]`,
        button: 'Запиши прехвърлянето',
      }
    default:
      return {
        title: 'Добавяне на приход',
        button: 'Запиши',
      }
  }
})

const title = `Хале: ${props.mincrement.mproduction.mhall?.name}, Процес №${props.mincrement.mproduction.id}`
</script>

<template>
  <Head :title="title"></Head>

  <DefaultLayout :title="title" icon="mdi-file-document-plus-outline">
    <q-page class="q-pa-none">
      <div class="page-container">
        <div class="body-panel">
          <div class="scrollable-content">
            <div class="column flex-grow flex-center">
              <h4 class="text-h6">{{ typeTitle.title }}</h4>
              <q-card class="q-pa-md full-width">
                <q-form class="q-gutter-xl" autofocus>
                  <q-input
                    :model-value="`[${mincrement.product?.nomenklature}] ${mincrement.product?.name}`"
                    class="col"
                    label="Продукт"
                    hint="Продукт избран в прихода"
                    readonly
                  />
                  <div class="row">
                    <div class="col-9 q-mr-md">
                      <q-input
                        v-model.number="form.quantity"
                        class="col"
                        type="number"
                        label="Количество"
                        hint="Количество от избрания продукт за приход."
                        :error="form.hasErrors"
                        :error-message="form.errors.quantity"
                        autofocus
                        numeric-keyboard-toggle
                      >
                        <template v-slot:append>
                          <span class="text-subtitle1">{{
                            mincrement.product.me
                          }}</span>
                        </template>
                      </q-input>
                    </div>
                    <div class="col">
                      <q-input
                        v-model.number="total"
                        type="number"
                        label="Наличност"
                        readonly
                        hint="Общо налично количество прасета в процеса"
                      >
                        <template v-slot:append>
                          <span class="text-subtitle1">{{
                            mincrement.product.me
                          }}</span>
                        </template>
                      </q-input>
                    </div>
                  </div>
                  <q-input
                    v-model.number="form.weight"
                    type="number"
                    label="Тегло"
                    :hint="`Общо тегло на ${
                      mincrement.type === 'Продажба'
                        ? 'продаваните'
                        : mincrement.type === 'Ремонт'
                        ? 'ремонтните'
                        : ''
                    } прасета`"
                    :error="form.hasErrors"
                    :error-message="form.errors.weight"
                  >
                    <template v-slot:append>
                      <span class="text-subtitle1">кг</span>
                    </template>
                  </q-input>
                  <q-input
                    v-model.number="form.price"
                    class="col"
                    type="number"
                    label="Цена *"
                    hint="Единична цена на избрания продукт за приход."
                    :error="form.hasErrors"
                    :error-message="form.errors.price"
                  />
                </q-form>
              </q-card>
            </div>
          </div>
        </div>
        <div class="footer-panel">
          <q-btn
            @click.prevent="mproductionsShow"
            color="primary"
            flat
            :label="`Процес №${mincrement.mproduction?.id}`"
            icon="mdi-menu-left"
          />

          <q-btn
            @click.prevent="mincrementsUpdate"
            :label="typeTitle.button"
            color="primary"
            icon="mdi-content-save-outline"
          />
        </div>
      </div>
    </q-page>
  </DefaultLayout>
</template>
