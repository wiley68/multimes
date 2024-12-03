<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const onSubmit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <GuestLayout>

        <Head title="Смяна на парола"></Head>

        <q-page class="column flex-center">
            <q-card
                class="q-pa-md"
                style="width: 400px; max-width: 90vw;"
            >
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
                            autofocus
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
                        <q-input
                            v-model="form.password_confirmation"
                            label="Потвърдете паролата"
                            type="password"
                            hint="Потвърдете паролата"
                            :error="form.hasErrors"
                            :error-message="form.errors.password_confirmation"
                        />
                        <div class="q-mt-lg row items-center justify-end">
                            <q-btn
                                label="Смяна на парола"
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
