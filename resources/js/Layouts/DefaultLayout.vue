<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const leftDrawerOpen = ref(false)

const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value
}

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <q-layout view="hHh lpR fFf">
        <q-header
            bordered
            class="bg-primary text-white select-none"
        >
            <q-toolbar>
                <q-btn
                    dense
                    flat
                    round
                    icon="mdi-menu"
                    @click="toggleLeftDrawer"
                />

                <q-toolbar-title>
                    <q-icon
                        name="mdi-pig-variant-outline"
                        size="md"
                    ></q-icon>
                    Мултимес
                </q-toolbar-title>

                <q-btn
                    color="secondary"
                    label="Basic Menu"
                >
                    <q-menu
                        transition-show="flip-right"
                        transition-hide="flip-left"
                    >
                        <q-list style="min-width: 100px">
                            <q-item
                                clickable
                                v-close-popup
                            >
                                <q-item-section avatar>
                                    <q-icon
                                        color="primary"
                                        name="mdi-bluetooth"
                                    />
                                </q-item-section>
                                <q-item-section>Потребители</q-item-section>
                            </q-item>
                            <q-separator />
                            <q-item
                                clickable
                                v-close-popup
                                @click="logout"
                            >
                                <q-item-section avatar>
                                    <q-icon
                                        color="negative"
                                        name="mdi-close"
                                    />
                                </q-item-section>
                                <q-item-section>Изход</q-item-section>
                            </q-item>
                        </q-list>
                    </q-menu>
                </q-btn>
            </q-toolbar>
        </q-header>

        <q-drawer
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            behavior="desktop"
            bordered
        >
            <!-- drawer content -->
        </q-drawer>

        <q-page-container>
            <slot></slot>
        </q-page-container>

        <q-footer
            bordered
            class="bg-grey-8 text-white"
        >
            <q-toolbar>
                <q-toolbar-title>
                    <q-avatar>
                        <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
                    </q-avatar>
                    <div>Title</div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>
    </q-layout>
</template>
