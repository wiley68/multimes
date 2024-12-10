<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue';

const slide = ref('process1')
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
              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.7"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border:1px solid #4DB6AC;border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      label="Хале 1 - 70%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  flat
                  style="min-width: 160px;"
                >Покажи процеса</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.3"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border:1px solid #4DB6AC;border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      label="Хале 2 - 30%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  flat
                  style="min-width: 160px;"
                >Покажи процеса</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.0"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      label="Хале 3"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  outline
                  style="min-width: 160px;"
                >Стартирай процес</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.0"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      label="Хале 4 - 0%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  outline
                  style="min-width: 160px;"
                >Стартирай процес</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.43"
                  color="teal-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border:1px solid #4DB6AC;border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="teal-10"
                      label="Хале 5 - 43%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  flat
                  style="min-width: 160px;"
                >Покажи процеса</q-btn>
              </div>

            </q-card-section>

            <q-separator />

            <q-card-actions
              align="around"
              style="height: 60px;"
            >
              <q-btn flat>Покажи халета Майки</q-btn>
              <q-btn flat>Покажи процеси Майки</q-btn>
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
              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.92"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border:1px solid #1A237E;border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      label="Хале 11 - 92%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  flat
                  style="min-width: 160px;"
                >Покажи процеса</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.0"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      label="Хале 12"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  outline
                  style="min-width: 160px;"
                >Стартирай процес</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.0"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      label="Хале 13"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  outline
                  style="min-width: 160px;"
                >Стартирай процес</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.12"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border:1px solid #1A237E;border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      label="Хале 14 - 12%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  flat
                  style="min-width: 160px;"
                >Покажи процеса</q-btn>
              </div>

              <div class="row q-gutter-x-sm q-my-sm">
                <q-linear-progress
                  class="col"
                  rounded
                  size="40px"
                  :value="0.23"
                  color="indigo-2"
                >
                  <div
                    class="absolute-full flex flex-center"
                    style="border:1px solid #1A237E;border-radius: 0.25rem;"
                  >
                    <q-badge
                      class="q-px-sm q-py-xs text-caption text-weight-medium"
                      style="user-select: none;"
                      color="white"
                      rounded
                      text-color="indigo-10"
                      label="Хале 15 - 23%"
                    />
                  </div>
                </q-linear-progress>
                <q-btn
                  flat
                  style="min-width: 160px;"
                >Покажи процеса</q-btn>
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
              Производствен процес 1 - Информация
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
              Производствен процес 2 - Информация
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
              Производствен процес 3 - Информация
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
              Производствен процес 4 - Информация
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
  </DefaultLayout>
</template>
