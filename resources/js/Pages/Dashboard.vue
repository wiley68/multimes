<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'

const props = defineProps({
  mhalls: {
    type: Object,
  },
  uhalls: {
    type: Object,
  },
})

const title = 'Табло'
const $q = useQuasar()
const { hasPermission, hasPermissions } = usePermission()

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

const confirmMproduction = (mhall) => {
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

const confirmUproduction = (uhall) => {
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
      production_days: 45,
    })
  }).onCancel(() => { }).onDismiss(() => { })
}
</script>

<template>

  <Head :title="title"></Head>

  <DefaultLayout
    :title="title"
    icon="mdi-file-chart-outline"
  >
    <div
      class="column q-px-sm q-gutter-y-sm"
      style="height: calc(100vh - 82px);"
    >
      <div
        v-if="hasPermissions(['create', 'update', 'view', 'delete'])"
        class="row wrap q-gutter-sm full-height"
      >
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
              class="column"
              style="flex-grow: 1; overflow-x: hidden;overflow-y: auto;"
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
                      :label="checkStatusMhall(mhall.mproductions) !== false ? `Хале: ${mhall.name} [Процес №${checkStatusMhall(mhall.mproductions)}: 70%]` : `Хале: ${mhall.name}`"
                    />
                  </div>
                </q-linear-progress>
                <template v-if="checkStatusMhall(mhall.mproductions) !== false">
                  <q-btn
                    flat
                    style="min-width: 190px;"
                    class="text-accent"
                    @click="router.get(route('mproductions.show', { mproduction: checkStatusMhall(mhall.mproductions) }))"
                  >Управлявай процеса</q-btn>
                </template>
                <template v-else>
                  <template v-if="hasPermission('create')">
                    <q-btn
                      outline
                      style="min-width: 190px;"
                      @click="confirmMproduction(mhall)"
                    >Стартирай процес</q-btn>
                  </template>
                  <template v-else>
                    <div style="min-width: 190px;"></div>
                  </template>
                </template>
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
              class="column"
              style="flex-grow: 1; overflow-x: hidden;overflow-y: auto;"
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
                      :label="checkStatusUhall(uhall.uproductions) !== false ? `Хале: ${uhall.name} [Процес №${checkStatusUhall(uhall.uproductions)}: 45%]` : `Хале: ${uhall.name}`"
                    />
                  </div>
                </q-linear-progress>
                <template v-if="checkStatusUhall(uhall.uproductions) !== false">
                  <q-btn
                    flat
                    style="min-width: 190px;"
                    class="text-accent"
                    @click="router.get(route('uproductions.show', { uproduction: checkStatusUhall(uhall.uproductions) }))"
                  >Управлявай процеса</q-btn>
                </template>
                <template v-else>
                  <template v-if="hasPermission('create')">
                    <q-btn
                      outline
                      style="min-width: 190px;"
                      @click="confirmUproduction(uhall)"
                    >Стартирай процес</q-btn>
                  </template>
                  <template v-else>
                    <div style="min-width: 190px;"></div>
                  </template>
                </template>
              </div>
            </q-card-section>

            <q-separator />

            <q-card-actions
              align="around"
              style="height: 60px;"
            >
              <q-btn
                flat
                @click="router.get(route('uhalls.show'))"
              >Покажи халета Угояване</q-btn>
              <q-btn
                flat
                @click="router.get(route('uproductions.index'))"
              >Покажи процеси Угояване</q-btn>
            </q-card-actions>
          </q-card>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>
