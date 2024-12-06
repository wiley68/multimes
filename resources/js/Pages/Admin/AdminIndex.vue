<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>

  <Head title="Административен панел"></Head>

  <DefaultLayout>
    <q-page class="flex flex-center">
      <q-card class="q-pa-md">
        <q-card-section>
          <div class="text-h6">Административен панел</div>
        </q-card-section>
      </q-card>
    </q-page>
  </DefaultLayout>
</template>
