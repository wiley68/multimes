<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

defineProps({
    factories: Array
})

const form = useForm({
    name: '',
    factory: null
})

const onSubmit = () => {
    form.post(route('silos.store'), {
        onFinish: () => form.reset('name', 'factory'),
    })
};

const onReset = () => {
    form.reset('name', 'factory')
}

const title = 'Нов Силоз'
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
                                        label="Силоз *"
                                        hint="Име на Силоза"
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />

                                    <q-select
                                        v-model="form.factory"
                                        :options="factories"
                                        option-label="name"
                                        label="Избери База"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.factory"
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
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Силози"
                        icon="mdi-menu-left"
                        @click="router.get(route('silos.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
