<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const leftDrawerOpen = ref(false)

const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value
}

const logout = () => { router.post(route('logout')) }

const toAdmin = () => { router.get(route('admin.index')) }

const toDashboard = () => { router.get(route('dashboard')) }
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
                    icon="menu"
                    @click="toggleLeftDrawer"
                    class="q-mr-sm"
                />

                <q-separator
                    dark
                    vertical
                    inset
                />

                <q-toolbar-title>
                    <q-icon
                        name="savings"
                        size="md"
                    ></q-icon>
                    Мултимес
                </q-toolbar-title>

                <q-space />

                <div class="q-mr-md text-subtitle1">
                    Мултимес
                </div>

                <q-separator
                    dark
                    vertical
                    inset
                />

                <q-btn-dropdown
                    stretch
                    flat
                    label="Меню"
                >
                    <q-list style="min-width: 100px">
                        <q-item
                            clickable
                            v-close-popup
                            @click="toAdmin"
                        >
                            <q-item-section avatar>
                                <q-icon
                                    color="primary"
                                    name="group"
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
                                    name="close"
                                />
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
            <q-scroll-area class="fit">
                <q-list class="q-pa-sm">
                    <q-item
                        clickable
                        v-close-popup
                        @click="toDashboard"
                        :active="route().current('dashboard')"
                        class="text-primary"
                        active-class="bg-blue-1"
                    >
                        <q-item-section avatar>
                            <q-icon
                                color="primary"
                                name="dashboard"
                            />
                        </q-item-section>
                        <q-item-section>Табло</q-item-section>
                    </q-item>

                    <q-separator />

                    <q-expansion-item
                        group="module1"
                        icon="store"
                        label="Майки"
                        expand-icon-class="text-primary"
                        header-class="text-primary"
                    >
                        <q-card>
                            <q-card-section>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-separator />

                    <q-expansion-item
                        group="module2"
                        icon="add_business"
                        label="Угояване"
                        expand-icon-class="text-primary"
                        header-class="text-primary"
                    >
                        <q-card>
                            <q-card-section>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-separator />

                    <q-expansion-item
                        group="nomenclature"
                        icon="settings"
                        label="Настройки"
                        expand-icon-class="text-primary"
                        header-class="text-primary"
                    >
                        <q-card>
                            <q-card-section>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-separator />
                </q-list>

            </q-scroll-area>
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
                    <div>Title</div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>
    </q-layout>
</template>
