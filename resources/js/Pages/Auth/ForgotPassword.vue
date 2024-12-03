<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm, router } from '@inertiajs/vue3'

defineProps({
    status: {
        type: String,
    },
})

const form = useForm({
    email: '',
});

const onSubmit = () => {
    form.post(route('password.email'));
}
</script>

<template>
    <GuestLayout>

        <Head title="Забравена парола"></Head>

        <q-page class="column flex-center">
            <div class="q-mb-md text-body2 text-gray">
                Забравена парола? няма проблеми Просто ни уведомете имейла си
                адрес и ние ще ви изпратим имейл с връзка за повторно задаване на парола, която ще позволи
                вие да изберете нов.
            </div>

            <div
                v-if="status"
                class="q-mb-md text-body2 text-green"
            >
                {{ status }}
            </div>

            <q-card
                class="q-pa-md"
                style="width: 400px; max-width: 90vw;"
            >
                <q-card-section class="q-ma-sm">
                    <q-form
                        @submit.prevent="onSubmit"
                        class="q-gutter-md"
                    >
                        <q-input
                            v-model="form.email"
                            label="Имейл *"
                            hint="Имейл за вход в системата"
                            autocomplete="email"
                            :error="form.hasErrors"
                            :error-message="form.errors.email"
                        />
                        <div class="q-mt-lg row items-center justify-end">
                            <q-btn
                                label="Връзка за нулиране на имейл парола"
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
