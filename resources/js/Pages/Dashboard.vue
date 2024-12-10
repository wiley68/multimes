<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>

  <Head title="Табло"></Head>

  <DefaultLayout>
    <div
      class="column"
      style="height: calc(100vh - 82px); gap: 5px; padding: 5px;"
    >
      <div style="flex: 1;display: flex;gap: 5px;">
        <div style="background-color: brown;flex: 1;">майки</div>
        <div style="background-color: cadetblue;flex: 1;">угояване</div>
      </div>
      <div style="background-color: aquamarine; flex: 1;display: flex;">активни</div>
    </div>
  </DefaultLayout>
</template>
