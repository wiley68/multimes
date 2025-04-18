<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { usePermission } from '@/composables/permissions'
import OverlayLoader from '@/Components/OverlayLoader.vue'

defineProps({
  title: String,
  icon: String,
})

const leftDrawerOpen = ref(false)
const { hasRole, hasPermissions } = usePermission()

const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

const currentDateTime = ref('')
const isExpandedNomenklature = ref(false)
const isExpandedMothers = ref(false)
const isExpandedUgoiavane = ref(false)
const isExpandedReport = ref(false)

const formatDateTime = () => {
  const now = new Date()
  const day = String(now.getDate()).padStart(2, '0')
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const year = String(now.getFullYear()).slice(-2)
  const hours = String(now.getHours()).padStart(2, '0')
  const minutes = String(now.getMinutes()).padStart(2, '0')
  return `${day}.${month}.${year} ${hours}:${minutes}`
}

const updateDateTime = () => {
  currentDateTime.value = formatDateTime()
}

let intervalId
onMounted(() => {
  updateDateTime()
  intervalId = setInterval(updateDateTime, 30000)
  let searchNomenklatures = [
    'Nomenklature/Cities',
    'Nomenklature/Factories',
    'Nomenklature/Mhalls',
    'Nomenklature/Uhalls',
    'Nomenklature/Silos',
    'Nomenklature/Products',
  ]
  if (searchNomenklatures.some((str) => usePage().component.includes(str))) {
    isExpandedNomenklature.value = true
  }
  let searchMothers = ['Mproductions']
  if (searchMothers.some((str) => usePage().component.includes(str))) {
    isExpandedMothers.value = true
  }
  let searchUgoiavane = ['Uproductions']
  if (searchUgoiavane.some((str) => usePage().component.includes(str))) {
    isExpandedUgoiavane.value = true
  }
  let searchReports = ['Reports/ProductionsIndex']
  if (searchReports.some((str) => usePage().component.includes(str))) {
    isExpandedReport.value = true
  }
})

onBeforeUnmount(() => {
  clearInterval(intervalId)
})
</script>

<template>
  <OverlayLoader />
  <q-layout view="hHh lpR fFf">
    <q-header bordered class="bg-primary text-white select-none">
      <q-toolbar>
        <q-btn
          dense
          flat
          round
          icon="mdi-menu"
          @click="toggleLeftDrawer"
          class="q-mr-sm"
        />

        <q-separator dark vertical inset />

        <q-toolbar-title style="flex: none">
          <q-icon name="mdi-pig-variant-outline" size="md"></q-icon>
          Мултимес
        </q-toolbar-title>

        <q-separator dark vertical inset />

        <q-toolbar-title>
          <div class="row items-center">
            <q-icon :name="icon" size="md" class="q-mr-sm"></q-icon>
            {{ title }}
          </div>
        </q-toolbar-title>

        <q-separator dark vertical inset />

        <q-btn-dropdown stretch flat :label="$page.props.auth.user.name">
          <q-list style="min-width: 100px">
            <q-item
              clickable
              v-close-popup
              @click="router.post(route('logout'))"
            >
              <q-item-section avatar>
                <q-icon color="negative" name="mdi-close" />
              </q-item-section>
              <q-item-section>Изход</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-drawer
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      behavior="desktop"
      bordered
    >
      <q-list class="full-height flex column">
        <template v-if="hasRole('admin')">
          <q-item
            clickable
            v-close-popup
            @click="router.get(route('admin.index'))"
            :active="route().current('admin.index')"
            class="text-primary"
            active-class="bg-blue-1"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-view-dashboard-outline" />
            </q-item-section>
            <q-item-section>Табло</q-item-section>
          </q-item>
        </template>
        <template v-else>
          <q-item
            clickable
            v-close-popup
            @click="router.get(route('dashboard'))"
            :active="route().current('dashboard')"
            class="text-primary"
            active-class="bg-blue-1"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-view-dashboard-outline" />
            </q-item-section>
            <q-item-section>Табло</q-item-section>
          </q-item>
        </template>

        <q-separator />

        <template v-if="hasRole('admin')">
          <q-item
            clickable
            v-close-popup
            class="text-primary"
            active-class="bg-blue-1"
            @click="router.get(route('users.index'))"
            :active="route().current('users.index')"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-account-multiple-outline" />
            </q-item-section>
            <q-item-section>Потребители</q-item-section>
          </q-item>

          <q-separator />

          <q-item
            clickable
            v-close-popup
            class="text-primary"
            active-class="bg-blue-1"
            @click="router.get(route('roles.index'))"
            :active="route().current('roles.index')"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-account-group-outline" />
            </q-item-section>
            <q-item-section>Роли</q-item-section>
          </q-item>

          <q-separator />

          <q-item
            clickable
            v-close-popup
            class="text-primary"
            active-class="bg-blue-1"
            @click="router.get(route('permissions.index'))"
            :active="route().current('permissions.index')"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-shield-key-outline" />
            </q-item-section>
            <q-item-section>Права</q-item-section>
          </q-item>

          <q-separator />
        </template>

        <template v-if="hasPermissions(['create', 'update', 'delete', 'view'])">
          <q-expansion-item
            v-model="isExpandedMothers"
            group="mothers"
            icon="mdi-home-import-outline"
            label="Майки"
            expand-icon-class="text-primary"
            header-class="text-primary"
          >
            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('mhalls.show'))"
              :active="usePage().component.includes('Mproductions/Mhalls')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-barn" />
              </q-item-section>
              <q-item-section>Халета Майки</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('mproductions.index'))"
              :active="usePage().component.includes('Mproductions')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-timer-play-outline" />
              </q-item-section>
              <q-item-section>Процеси Майки</q-item-section>
            </q-item>
          </q-expansion-item>

          <q-separator />

          <q-expansion-item
            v-model="isExpandedUgoiavane"
            group="ugoiavane"
            icon="mdi-home-export-outline"
            label="Угояване"
            expand-icon-class="text-primary"
            header-class="text-primary"
          >
            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('uhalls.show'))"
              :active="usePage().component.includes('Uproductions/Uhalls')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-barn" />
              </q-item-section>
              <q-item-section>Халета Угояване</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('uproductions.index'))"
              :active="usePage().component.includes('Uproductions')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-timer-play-outline" />
              </q-item-section>
              <q-item-section>Процеси Угояване</q-item-section>
            </q-item>
          </q-expansion-item>

          <q-separator />

          <q-expansion-item
            v-model="isExpandedNomenklature"
            group="nomenklature"
            icon="mdi-folder-table-outline"
            label="Номенклатури"
            expand-icon-class="text-primary"
            header-class="text-primary"
          >
            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('cities.index'))"
              :active="usePage().component.includes('Nomenklature/Cities')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-home-city-outline" />
              </q-item-section>
              <q-item-section>Населени места</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('factories.index'))"
              :active="usePage().component.includes('Nomenklature/Factories')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-factory" />
              </q-item-section>
              <q-item-section>Производствени Бази</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('silos.index'))"
              :active="usePage().component.includes('Nomenklature/Silos')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-silo-outline" />
              </q-item-section>
              <q-item-section>Силози</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('mhalls.index'))"
              :active="usePage().component.includes('Nomenklature/Mhalls')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-barn" />
              </q-item-section>
              <q-item-section>Халета за майки</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('uhalls.index'))"
              :active="usePage().component.includes('Nomenklature/Uhalls')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-barn" />
              </q-item-section>
              <q-item-section>Халета за угояване</q-item-section>
            </q-item>

            <q-separator />

            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('products.index'))"
              :active="usePage().component.includes('Nomenklature/Products')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-package-variant-closed" />
              </q-item-section>
              <q-item-section>Продукти</q-item-section>
            </q-item>
          </q-expansion-item>

          <q-separator />

          <q-item
            clickable
            class="text-primary"
            active-class="bg-blue-1"
            @click="router.get(route('deliveries.index'))"
            :active="usePage().component.includes('Deliveries')"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-truck-delivery-outline" />
            </q-item-section>
            <q-item-section>Доставки</q-item-section>
          </q-item>

          <q-separator />

          <q-item
            clickable
            class="text-primary"
            active-class="bg-blue-1"
            @click="router.get(route('stores.index'))"
            :active="usePage().component.includes('Stores')"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="mdi-store-outline" />
            </q-item-section>
            <q-item-section>Наличности</q-item-section>
          </q-item>

          <q-separator />
        </template>

        <template v-if="hasPermissions(['view'])">
          <q-expansion-item
            v-model="isExpandedReport"
            group="report"
            icon="mdi-chart-bar"
            label="Справки"
            expand-icon-class="text-primary"
            header-class="text-primary"
          >
            <q-item
              clickable
              class="text-secondary"
              active-class="bg-blue-1"
              @click="router.get(route('reports.index'))"
              :active="usePage().component.includes('Reports/ProductionsIndex')"
            >
              <q-item-section avatar>
                <q-icon color="secondary" name="mdi-timer-play-outline" />
              </q-item-section>
              <q-item-section>Производствени процеси</q-item-section>
            </q-item>
          </q-expansion-item>

          <q-separator />
        </template>

        <q-space />

        <q-separator />

        <q-item
          clickable
          v-close-popup
          class="text-negative"
          @click="router.post(route('logout'))"
        >
          <q-item-section avatar>
            <q-icon color="negative" name="close" />
          </q-item-section>
          <q-item-section>Изход</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <slot></slot>
    </q-page-container>

    <q-footer bordered class="bg-grey-2 text-grey-10 q-custom-toolbar">
      <q-toolbar class="select-none q-custom-toolbar">
        <q-toolbar-title class="text-left text-subtitle1 text-title"
          >{{ $page.props.app_name }}: v.
          {{ $page.props.version }}</q-toolbar-title
        >
        <q-separator vertical />
        <template v-if="$page.props.auth.user.roles.length">
          <q-toolbar-title
            v-for="role in $page.props.auth.user.roles"
            class="text-left text-subtitle1 text-title"
            :class="
              role === 'admin'
                ? 'text-red-8'
                : role === 'moderator'
                ? 'text-orange-8'
                : role === 'user'
                ? 'text-green-8'
                : 'text-gray-10'
            "
            >{{ role }}</q-toolbar-title
          >
          <q-separator vertical />
        </template>
        <q-toolbar-title class="text-left text-subtitle1 text-title">{{
          $page.props.auth.user.name
        }}</q-toolbar-title>
        <q-separator vertical />
        <q-toolbar-title class="text-left text-subtitle1 text-title">{{
          $page.props.auth.user.email
        }}</q-toolbar-title>
        <q-separator vertical />
        <q-toolbar-title class="text-right text-subtitle1 text-grey-8">{{
          currentDateTime
        }}</q-toolbar-title>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<style>
.q-custom-toolbar {
  min-height: 30px !important;
}

.text-title {
  max-width: max-content;
  white-space: nowrap;
}

.page-container {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 82px);
}

.body-panel {
  flex: 1;
  overflow-y: auto;
  border-bottom: 1px solid #e0e0e0;
}

.header-panel {
  display: flex;
  align-items: center;
  width: 100%;
  background-color: #ffffff;
  border-bottom: 1px solid #e0e0e0;
}

.footer-panel {
  height: 48px;
  display: flex;
  gap: 5px;
  align-items: center;
  width: 100%;
  background-color: #ffffff;
  padding-left: 4px;
  padding-right: 4px;
}

.scrollable-content {
  padding: 16px;
}
</style>
