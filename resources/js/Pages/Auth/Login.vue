<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm, router } from '@inertiajs/vue3'

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const onSubmit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <GuestLayout>

    <Head title="Вход"></Head>

    <q-page class="column flex-center">
      <div
        v-if="status"
        class="q-mb-md text-body2 text-green"
      >
        {{ status }}
      </div>

      <q-card
        class="q-pa-md"
        style="width: 400px; max-width: 90vw;"
      >
        <q-card-section class="text-h5 text-center q-pa-sm q-ma-sm">
          Вход
        </q-card-section>

        <q-card-section class="q-ma-sm">
          <q-form
            @submit="onSubmit"
            class="q-gutter-md"
          >
            <q-input
              v-model="form.email"
              label="Имейл *"
              hint="Имейл за вход в системата"
              autocomplete="email"
              :error="form.hasErrors"
              :error-message="form.errors.email"
            />
            <q-input
              v-model="form.password"
              label="Парола"
              type="password"
              hint="Парола за вход в системата"
              :error="form.hasErrors"
              :error-message="form.errors.password"
            />
            <div class="q-mt-lg row items-center justify-end">
              <q-btn
                v-if="canResetPassword"
                @click="router.get(route('password.request'))"
                flat
                class="rounded text-sm"
                text-color="grey-6"
                label="Забравена парола?"
                style="text-decoration: underline"
              />

              <q-btn
                label="Вход"
                color="primary"
                type="submit"
                class="full-width q-mt-md"
              />
            </div>
          </q-form>
        </q-card-section>

      </q-card>
    </q-page>

  </GuestLayout>
</template>
