<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
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
  type: {
    type: String,
    required: true,
  },
})

const form = useForm({
  mproduction_id: props.mproduction.id,
  product: props.product,
  quantity: 1,
  weight: 0.0,
  price: props.type === 'Умрели' ? 0.0 : props.mproduction.price,
  status: 0,
  type: props.type,
})

const $q = useQuasar()
const mincrementsStore = () => {
  form.post(route('mincrements.store'), {
    onFinish: () => {
      form.reset('quantity', 'weight')
    },
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
  router.get(route('mproductions.show', props.mproduction.id))
}

const me = ref('')
const total = ref(0)

onMounted(() => {
  me.value = props.mproduction.product?.me
  total.value = props.mproduction.stock
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

const typeTitle = computed(() => {
  switch (props.type) {
    case 'Продажба':
      return {
        title: `Продажба [${props.product.type}]`,
        button: 'Запиши продажбата',
      }
    case 'Прехвърляне':
      return {
        title: `Прехвърляне [${props.product.type} >> ${nextProduct(
          props.product.type
        )}]`,
        button: 'Запиши прехвърлянето',
      }
    case 'Умрели':
      return {
        title: `Смърт [${props.product.type}]`,
        button: 'Запиши прехвърлянето',
      }
    default:
      return {
        title: 'Добавяне на приход',
        button: 'Запиши',
      }
  }
})

const title = `Хале: ${props.mproduction.mhall?.name}, Процес №${props.mproduction.id}`
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
                    :model-value="`[${product.nomenklature}] ${product.name}`"
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
                        autofocus
                        :error="form.hasErrors"
                        :error-message="form.errors.quantity"
                      >
                        <template v-slot:append>
                          <span class="text-subtitle1">{{ product.me }}</span>
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
                          <span class="text-subtitle1">{{ me }}</span>
                        </template>
                      </q-input>
                    </div>
                  </div>
                  <q-input
                    v-model.number="form.weight"
                    type="number"
                    label="Тегло"
                    :hint="`Общо тегло на ${
                      type === 'Продажба'
                        ? 'продаваните'
                        : type === 'Прехвърляне'
                        ? 'прехвърляните'
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
            :label="`Процес №${mproduction.id}`"
            icon="mdi-menu-left"
          />

          <q-btn
            @click.prevent="mincrementsStore"
            :label="typeTitle.button"
            color="primary"
            icon="mdi-plus"
          />
        </div>
      </div>
    </q-page>
  </DefaultLayout>
</template>
