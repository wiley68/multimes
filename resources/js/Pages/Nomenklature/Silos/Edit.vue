<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    silo: {
        type: Object,
        required: true
    },
    factories: Array
})

const form = useForm({
    name: props.silo?.name,
    factory: props.silo?.factory,
})

const onSubmit = () => {
    form.put(route('silos.update', props.silo.id), {
        onFinish: () => {
            form.reset('name', 'factory')
        },
    })
};

const title = 'Силоз'
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-edit-outline"
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
                                        label="Силоз *"
                                        hint="Име на Силоза"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />

                                    <q-select
                                        v-model="form.factory"
                                        :options="factories"
                                        option-label="name"
                                        label="Избери база"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.factory"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Силози"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('silos.index'))"
                    />

                    <q-btn
                        @click.prevent="onSubmit"
                        label="Запиши"
                        type="submit"
                        icon="mdi-content-save-outline"
                        color="primary"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
