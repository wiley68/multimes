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

const onReset = () => {
    form.reset('name', 'email', 'password', 'password_confirmation')
}
</script>

<template>

    <Head title="Нов Потребител"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Потребители"
                        icon="mdi-menu-left"
                        @click="router.get(route('users.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Нов Потребител</h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">
                <q-card
                    class="q-pa-md"
                    style="width: 800px; max-width: 100%;"
                >
                    <q-form
                        @submit.prevent="onSubmit"
                        @reset="onReset"
                        class="q-gutter-md"
                    >
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

                        <div>
                            <q-btn
                                label="Създай"
                                type="submit"
                                color="primary"
                            />
                            <q-btn
                                label="Откажи"
                                type="reset"
                                color="primary"
                                flat
                                class="q-ml-sm"
                            />
                        </div>
                    </q-form>
                </q-card>
            </div>
        </q-page>
    </DefaultLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>