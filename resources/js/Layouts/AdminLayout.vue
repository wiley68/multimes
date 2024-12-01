<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePermission } from '@/composables/permissions';

const leftDrawerOpen = ref(false)
const { hasRole } = usePermission()

const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value
}

const logout = () => { router.post(route('logout')) }
const toAdmin = () => { router.get(route('admin.index')) }
const toDashboard = () => { router.get(route('dashboard')) }
const toUsers = () => { router.get(route('users.index')) }
const toRoles = () => { router.get(route('roles.index')) }
const toPermissins = () => { router.get(route('permissions.index')) }

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

                <q-separator
                    dark
                    vertical
                    inset
                />

                <q-btn-dropdown
                    stretch
                    flat
                    :label="$page.props.auth.user.name + ' - ' + $page.props.auth.user.roles[0]"
                >
                    <q-list style="min-width: 100px">
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
            <q-list class="full-height flex column">
                <template v-if="hasRole('admin')">
                    <q-item
                        clickable
                        v-close-popup
                        @click="toAdmin"
                        :active="route().current('admin.index')"
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
                </template>
                <template v-else>
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
                </template>

                <q-separator />

                <template v-if="hasRole('admin')">
                    <q-item
                        clickable
                        v-close-popup
                        class="text-primary"
                        active-class="bg-blue-1"
                        @click="toUsers"
                        :active="route().current('users.index')"
                    >
                        <q-item-section avatar>
                            <q-icon
                                color="primary"
                                name="person"
                            />
                        </q-item-section>
                        <q-item-section>Потребители</q-item-section>
                    </q-item>

                    <q-separator />

                    <q-item
                        clickable
                        v-close-popup
                        class="text-primary"
                        active-class="bg-blue-1"
                        @click="toRoles"
                        :active="route().current('roles.index')"
                    >
                        <q-item-section avatar>
                            <q-icon
                                color="primary"
                                name="groups"
                            />
                        </q-item-section>
                        <q-item-section>Роли</q-item-section>
                    </q-item>

                    <q-separator />

                    <q-item
                        clickable
                        v-close-popup
                        class="text-primary"
                        active-class="bg-blue-1"
                        @click="toPermissins"
                        :active="route().current('permissions.index')"
                    >
                        <q-item-section avatar>
                            <q-icon
                                color="primary"
                                name="verified_user"
                            />
                        </q-item-section>
                        <q-item-section>Права</q-item-section>
                    </q-item>

                    <q-separator />
                </template>
                <template v-else>
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
                </template>

                <q-space />

                <q-separator />

                <q-item
                    clickable
                    v-close-popup
                    class="text-negative"
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
        </q-drawer>

        <q-page-container>
            <slot></slot>
        </q-page-container>

        <q-footer
            bordered
            class="bg-grey-8 text-white q-custom-toolbar"
        >
            <q-toolbar class="select-none q-custom-toolbar">
                <q-toolbar-title class="text-left text-subtitle1">{{ $page.props.app_name }}: v. {{ $page.props.version
                    }}</q-toolbar-title>
                <q-toolbar-title class="text-right text-subtitle1">Avalon</q-toolbar-title>
            </q-toolbar>
        </q-footer>
    </q-layout>
</template>

<style scoped>
.q-custom-toolbar {
    min-height: 30px !important;
}
</style>