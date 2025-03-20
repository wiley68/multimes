<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
  uincrement: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  uproduction_id: props.uincrement.uproduction?.id,
  product: props.uincrement.product,
  quantity: props.uincrement.quantity,
  weight: props.uincrement.weight,
  price: props.uincrement.price,
  status: props.uincrement.status,
  type: props.uincrement.type,
})

const $q = useQuasar()
const uincrementsUpdate = () => {
  form.put(route('uincrements.update', props.uincrement.id), {
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

const total = ref(0)
onMounted(() => {
  total.value = props.uincrement?.uproduction?.stock
})

const typeTitle = computed(() => {
  switch (props.uincrement.type) {
    case 'Продажба':
      return {
        title: 'Продажба на прасета',
        button: 'Запиши продажбата',
      }
    case 'Умрели':
      return {
        title: 'Прасета умрели',
        button: 'Запиши прехвърлянето',
      }
    default:
      return {
        title: 'Добавяне на приход',
        button: 'Запиши',
      }
  }
})

const title = `${typeTitle.value.title} към Процес №${props.uincrement?.uproduction?.id}`
</script>

<template>
  <Head :title="title"></Head>

  <DefaultLayout :title="title" icon="mdi-file-document-plus-outline">
    <q-page class="q-pa-none">
      <div class="page-container">
        <div class="body-panel">
          <div class="scrollable-content">
            <div class="column flex-grow flex-center">
              <q-card class="q-pa-md full-width">
                <q-form class="q-gutter-xl" autofocus>
                  <q-input
                    :model-value="`[${uincrement.product?.nomenklature}] ${uincrement.product?.name}`"
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
                            uincrement.product.me
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
                            uincrement.product.me
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
                      uincrement.type === 'Продажба' ? 'продаваните' : ''
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
            @click.prevent="
              router.get(route('uproductions.show', uincrement.uproduction?.id))
            "
            color="primary"
            flat
            :label="`Процес №${uincrement.uproduction?.id}`"
            icon="mdi-menu-left"
          />
          <q-btn
            @click.prevent="uincrementsUpdate"
            :label="typeTitle.button"
            color="primary"
            icon="mdi-content-save-outline"
          />
        </div>
      </div>
    </q-page>
  </DefaultLayout>
</template>
