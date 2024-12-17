<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head } from '@inertiajs/vue3'

const title = 'Табло Администрация'
</script>

<template>

  <Head :title="title"></Head>

  <DefaultLayout
    :title="title"
    icon="mdi-file-chart-outline"
  >
    <q-page class="flex flex-center">
      <q-card class="q-pa-md">
        <q-card-section>
          <div class="text-h6">{{ title }}</div>
        </q-card-section>
      </q-card>
    </q-page>
  </DefaultLayout>
</template>
