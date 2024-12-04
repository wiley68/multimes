<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
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

    <Head title="Създаване на потребител"></Head>

    <AdminLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Потребители"
                        icon="chevron_left"
                        @click="router.get(route('users.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Създаване на потребители</h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">
                <q-card
                    class="q-pa-md"
                    style="width: 400px;"
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
    </AdminLayout>
</template>
