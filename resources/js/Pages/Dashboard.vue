<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue';
import { useQuasar } from 'quasar'

const props = defineProps({
  mhalls: {
    type: Object,
  },
  uhalls: {
    type: Object,
  },
})

const $q = useQuasar()
const slide = ref('process1')

const checkStatusMhall = (val) => {
  if (Array.isArray(val) && val.length > 0) {
    const foundMproject = val.find(item => item.status === 1)
    if (foundMproject === undefined) {
      return false
    } else {
      return foundMproject.id
    }
  } else {
    return false
  }
}

const checkStatusUhall = (val) => {
  if (Array.isArray(val) && val.length > 0) {
    const foundUproject = val.find(item => item.status === 1)
    if (foundUproject === undefined) {
      return false
    } else {
      return foundUproject.id
    }
  } else {
    return false
  }
}

const mhallBtnClick = (mhall) => {
  if (checkStatusMhall(mhall.mproductions) !== false) {
    router.get(route('mproductions.show', { mproduction: checkStatusMhall(mhall.mproductions) }))
  } else {
    $q.dialog({
      title: 'Потвърди',
      message: 'Ще бъде стартиран нов производствен процес! Процеса е необратим. Съгласен ли сте с това?',
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
      router.post(route('mproductions.store'), {
        status: 1,
        mhall: mhall,
      })
    }).onCancel(() => { }).onDismiss(() => { })
  }
}

const uhallBtnClick = (uhall) => {
  if (checkStatusUhall(uhall.uproductions) !== false) {
    router.get(route('uproductions.show', { uproduction: checkStatusUhall(uhall.uproductions) }))
  } else {
    $q.dialog({
      title: 'Потвърди',
      message: 'Ще бъде стартиран нов производствен процес! Процеса е необратим. Съгласен ли сте с това?',
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
      router.post(route('uproductions.store'), {
        status: 1,
        uhall: uhall,
      })
    }).onCancel(() => { }).onDismiss(() => { })
  }
}
</script>

<template>

  <Head title="Табло"></Head>

  <DefaultLayout>
    <div
      class="column q-px-sm q-gutter-y-sm"
      style="height: calc(100vh - 82px);"
    >
      <div class="row wrap q-gutter-sm">
        <div
          class="col"
          style="min-width: 300px;"
        >
          <q-card class="column fit">
            <q-card-section class="bg-teal text-white">
              <div class="text-h6">МАЙКИ</div>
              <div class="text-subtitle2">Халета за прасета майки</div>
            </q-card-section>

            <q-card-section
              style="max-height: 240px; display: flex;flex-direction: column;overflow-x: hidden;overflow-y: auto;"
            >
              <div
                class="row q-gutter-x-sm q-my-sm"
                v-for="mhall in mhalls"
                :key="mhall.id"
              >
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="checkStatusMhall(mhall.mproductions) !== false ? 0.70 : 0.00"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                    :style="checkStatusMhall(mhall.mproductions) !== false ? 'border:1px solid #4DB6AC;' : ''"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      :label="checkStatusMhall(mhall.mproductions) !== false ? mhall.name + ' - 70%' : mhall.name"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  :flat="checkStatusMhall(mhall.mproductions) !== false"
                  :outline="checkStatusMhall(mhall.mproductions) === false"
                  style="min-width: 190px;"
                  :class="checkStatusMhall(mhall.mproductions) !== false ? 'text-accent' : ''"
                  @click="mhallBtnClick(mhall)"
                >{{ checkStatusMhall(mhall.mproductions) !== false ? 'Управлявай процеса' : 'Стартирай процес'
                  }}</q-btn>
              </div>
            </q-card-section>

            <q-separator />

            <q-card-actions
              align="around"
              style="height: 60px;"
            >
              <q-btn
                @click="router.get(route('mhalls.show'))"
                flat
              >Покажи халета Майки</q-btn>
              <q-btn
                @click="router.get(route('mproductions.index'))"
                flat
              >Покажи процеси Майки</q-btn>
            </q-card-actions>
          </q-card>
        </div>
        <div
          class="col"
          style="min-width: 300px;"
        >
          <q-card class="column fit">
            <q-card-section class="bg-indigo text-white">
              <div class="text-h6">УГОЯВАНЕ</div>
              <div class="text-subtitle2">Халета за прасета угояване</div>
            </q-card-section>

            <q-card-section
              style="max-height: 240px; display: flex;flex-direction: column;overflow-x: hidden;overflow-y: auto;"
            >
              <div
                class="row q-gutter-x-sm q-my-sm"
                v-for="uhall in uhalls"
                :key="uhall.id"
              >
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="checkStatusUhall(uhall.uproductions) !== false ? 0.45 : 0.00"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                    :style="checkStatusUhall(uhall.uproductions) !== false ? 'border:1px solid #1A237E;' : ''"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      :label="checkStatusUhall(uhall.uproductions) !== false ? uhall.name + ' - 45%' : uhall.name"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  :flat="checkStatusUhall(uhall.uproductions) !== false"
                  :outline="checkStatusUhall(uhall.uproductions) === false"
                  style="min-width: 190px;"
                  :class="checkStatusUhall(uhall.uproductions) !== false ? 'text-accent' : ''"
                  @click="uhallBtnClick(uhall)"
                >{{ checkStatusUhall(uhall.uproductions) !== false ? 'Управлявай процеса' : 'Стартирай процес'
                  }}</q-btn>
              </div>
            </q-card-section>

            <q-separator />

            <q-card-actions
              align="around"
              style="height: 60px;"
            >
              <q-btn flat>Покажи халета Угояване</q-btn>
              <q-btn flat>Покажи процеси Угояване</q-btn>
            </q-card-actions>
          </q-card>
        </div>
      </div>
      <div class="col row">
        <q-carousel
          v-model="slide"
          swipeable
          animated
          control-type="regular"
          control-color="primary"
          navigation
          padding
          arrows
          height="max-height"
          class="text-primary rounded-borders full-width"
        >
          <q-carousel-slide
            name="process1"
            class="column no-wrap flex-center"
          >
            <q-icon
              name="mdi-timer-play-outline"
              size="56px"
            />
            <div class="q-mt-md text-center">
              Продукционен процес 1 - Информация
            </div>
          </q-carousel-slide>
          <q-carousel-slide
            name="tv"
            class="column no-wrap flex-center"
          >
            <q-icon
              name="mdi-timer-play-outline"
              size="56px"
            />
            <div class="q-mt-md text-center">
              Продукционен процес 2 - Информация
            </div>
          </q-carousel-slide>
          <q-carousel-slide
            name="layers"
            class="column no-wrap flex-center"
          >
            <q-icon
              name="mdi-timer-play-outline"
              size="56px"
            />
            <div class="q-mt-md text-center">
              Продукционен процес 3 - Информация
            </div>
          </q-carousel-slide>
          <q-carousel-slide
            name="map"
            class="column no-wrap flex-center"
          >
            <q-icon
              name="mdi-timer-play-outline"
              size="56px"
            />
            <div class="q-mt-md text-center">
              Продукционен процес 4 - Информация
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
  </DefaultLayout>
</template>
