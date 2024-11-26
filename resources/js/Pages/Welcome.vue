<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <Head title="Начало"></Head>
  <div class="bg-gray-50 text-black/50">
    <div
      class="relative flex flex-col min-h-screen items-center justify-center selection:bg-[#FF2D20] selection:text-white"
    >
      <nav v-if="canLogin" class="-mx-3 flex justify-end mb-24">
        <Link
          v-if="$page.props.auth.user"
          :href="route('dashboard')"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
        >
          Табло
        </Link>

        <template v-else>
          <Link
            :href="route('login')"
            class="rounded-md px-3 py-2 text-black font-medium text-3xl ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
          >
            ВХОД
          </Link>
        </template>
      </nav>

      <div class="flex lg:col-start-2 lg:justify-center">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          class="h-16 w-16 text-orange-600"
          fill="currentColor"
        >
          <path
            d="M15 10C15 9.45 15.45 9 16 9C16.55 9 17 9.45 17 10S16.55 11 16 11 15 10.55 15 10M22 7.5V14.47L19.18 15.41L17.5 21H12V19H10V21H4.5C4.5 21 2 12.54 2 9.5S4.46 4 7.5 4H12.5C13.41 2.79 14.86 2 16.5 2C17.33 2 18 2.67 18 3.5C18 3.71 17.96 3.9 17.88 4.08C17.74 4.42 17.62 4.81 17.56 5.23L19.83 7.5H22M20 9.5H19L15.5 6C15.5 5.35 15.59 4.71 15.76 4.09C14.79 4.34 14 5.06 13.67 6H7.5C5.57 6 4 7.57 4 9.5C4 11.38 5.22 16.15 6 19H8V17H14V19H16L17.56 13.85L20 13.03V9.5Z"
          />
        </svg>
      </div>

      <main class="mt-6">
        <div class="flex justify-center items-center text-6xl font-bold">
          МУЛТИМЕС
        </div>
      </main>

      <footer class="py-16 text-center text-sm text-black">
        Laravel v{{ laravelVersion }} (Avalon)
      </footer>
    </div>
  </div>
</template>
