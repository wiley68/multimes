<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import VueMultiselect from 'vue-multiselect'

defineProps({
    roles: Array,
    permissions: Array
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
    permissions: []
})

const onSubmit = () => {
    form.post(route('users.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation')
        },
    })
};

const title = 'Потребител'
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-plus-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <div class="column flex-grow flex-center">
                            <q-card class="q-pa-md full-width">
                                <q-form class="q-gutter-md">
                                    <q-input
                                        v-model="form.name"
                                        label="Потребител *"
                                        hint="Име на потребителя"
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />

                                    <q-input
                                        v-model="form.email"
                                        label="Имейл *"
                                        hint="Имейл на потребителя"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.email"
                                    />

                                    <q-input
                                        v-model="form.password"
                                        label="Парола *"
                                        hint="Парола на потребителя"
                                        type="password"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.password"
                                    />

                                    <q-input
                                        v-model="form.password_confirmation"
                                        label="Повторно парола *"
                                        hint="Повтори парола на потребителя"
                                        type="password"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.password_confirmation"
                                    />

                                    <div class="q-my-lg">
                                        <VueMultiselect
                                            v-model="form.roles"
                                            :options="roles"
                                            :multiple="true"
                                            :close-on-select="true"
                                            placeholder="Добави роли"
                                            label="name"
                                            track-by="name"
                                        />
                                    </div>

                                    <div class="q-my-lg">
                                        <VueMultiselect
                                            v-model="form.permissions"
                                            :options="permissions"
                                            :multiple="true"
                                            :close-on-select="true"
                                            placeholder="Добави права"
                                            label="name"
                                            track-by="name"
                                        />
                                    </div>
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Потребители"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('users.index'))"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши"
                        icon="mdi-content-save-outline"
                        color="primary"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>