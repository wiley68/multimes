<script setup>
import { Head, router } from '@inertiajs/vue3'

defineProps({
  version: {
    type: String,
    required: true,
  },
})
</script>

<template>

  <Head title="Начало"></Head>
  <q-layout view="hHh lpR fFf">

    <q-page-container>
      <div
        class="column absolute-full flex flex-center"
        style="user-select: none;"
      >
        <template v-if="$page.props.auth.user">
          <q-btn
            @click="router.get(route('dashboard'))"
            color="primary"
            icon="dashboard"
            size="lg"
            label="Табло"
          />
        </template>
        <template v-else>
          <q-btn
            @click="router.get(route('login'))"
            color="primary"
            icon="login"
            size="lg"
            label="Вход"
          />
        </template>
        <h1 class="text-weight-medium">МУЛТИМЕС</h1>
        <p class="text-subtitle1 text-weight-light">Laravel v.{{ version }} - Avalon</p>
      </div>
    </q-page-container>

  </q-layout>
</template>