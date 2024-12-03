<script setup>
import { Head, router } from '@inertiajs/vue3'
import { usePermission } from '@/composables/permissions';
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
  version: {
    type: String,
    required: true,
  },
})

const { hasUser, hasRole } = usePermission()
</script>

<template>

  <Head title="Начало"></Head>

  <GuestLayout>
    <div
      class="column absolute-full flex flex-center"
      style="user-select: none;"
    >
      <template v-if="hasUser()">
        <q-btn
          v-if="hasRole('admin')"
          @click="router.get(route('admin.index'))"
          class="text-primary"
          icon="dashboard"
          size="lg"
          label="Табло"
        />
        <q-btn
          v-else
          @click="router.get(route('dashboard'))"
          class="text-primary"
          icon="dashboard"
          size="lg"
          label="Табло"
        />
      </template>
      <template v-else>
        <q-btn
          @click="router.get(route('login'))"
          class="text-primary"
          icon="login"
          size="lg"
          label="Вход"
        />
      </template>
      <h1 class="text-weight-medium text-primary">МУЛТИМЕС</h1>
      <p class="text-subtitle1 text-weight-light">Laravel v.{{ version }} - Avalon</p>
    </div>
  </GuestLayout>

</template>