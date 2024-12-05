import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import { Quasar, Dialog, LoadingBar } from 'quasar'
import '@quasar/extras/material-icons/material-icons.css'
import 'quasar/src/css/index.sass'

const appName = import.meta.env.VITE_APP_NAME || 'Мултимес'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(Quasar, {
        plugins: {
          Dialog,
          LoadingBar
        },
        config: {
          loadingBar: {
            color: '#ea580c',
            size: '5px',
            position: 'top',
          },
        },
      })
      .mount(el)
  },
})

import { router } from '@inertiajs/vue3'

router.on('start', () => {
  LoadingBar.start()
})

router.on('finish', () => {
  LoadingBar.stop()
})
