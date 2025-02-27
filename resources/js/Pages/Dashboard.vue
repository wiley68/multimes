<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { usePermission } from '@/composables/permissions'
import moment from 'moment'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import { ref } from 'vue'

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

const confirmMproduction = (mhall) => {
  $q.dialog({
    title: 'Потвърди',
    message: `Ще бъде стартиран нов производствен процес! Процеса е необратим. Съгласен ли сте с това?`,
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

const showPrompt = ref(false)
const selectedUhall = ref(null)

const confirmUproduction = (uhall) => {
  selectedUhall.value = uhall
  showPrompt.value = true
}

const handleOk = (data) => {
  router.post(route('uproductions.store'), {
    status: 1,
    uhall: selectedUhall.value,
    production_days: 45,
    group_number: data.groupNumber,
    partida_number: data.partidaNumber
  });

  showPrompt.value = false
}

const handleCancel = () => {
  showPrompt.value = false
}

const getDaysBetweenTodayAndDate = (targetDate) => {
  const today = moment().startOf('day')
  const target = moment(targetDate).startOf('day')
  return today.diff(target, 'days')
}

const productionPurcent = (production) => {
  if (production !== null) {
    const days = getDaysBetweenTodayAndDate(production.created_at)
    return parseFloat((days / parseFloat(production.production_days)).toFixed(2))
  } else {
    return 0
  }
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
                  :value="productionPurcent(mhall.mproduction)"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                    :style="mhall.mproduction !== null ? 'border:1px solid #4DB6AC;' : ''"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      :label="mhall.mproduction !== null ? `Хале: ${mhall.name} [Процес №${mhall.mproduction.id}: ${(productionPurcent(mhall.mproduction) * 100).toFixed(2)}%]` : `Хале: ${mhall.name}`"
                    />
                  </div>
                </q-linear-progress>
                <template v-if="mhall.mproduction !== null">
                  <q-btn
                    flat
                    style="min-width: 190px;"
                    class="text-accent"
                    @click="router.get(route('mproductions.show', { mproduction: mhall.mproduction }))"
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
                  :value="productionPurcent(uhall.uproduction)"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                    :style="uhall.uproduction !== null ? 'border:1px solid #1A237E;' : ''"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      :label="uhall.uproduction !== null ? `Хале: ${uhall.name} [Процес №${uhall.uproduction.id}: ${(productionPurcent(uhall.uproduction) * 100).toFixed(2)}%]` : `Хале: ${uhall.name}`"
                    />
                  </div>
                </q-linear-progress>
                <template v-if="uhall.uproduction !== null">
                  <q-btn
                    flat
                    style="min-width: 190px;"
                    class="text-accent"
                    @click="router.get(route('uproductions.show', { uproduction: uhall.uproduction }))"
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
