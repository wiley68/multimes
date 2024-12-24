<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    factory: {
        type: Object,
        required: true
    },
    cities: Array
})

const form = useForm({
    name: props.factory?.name,
    city: props.factory?.city,
})

const onSubmit = () => {
    form.put(route('factories.update', props.factory.id), {
        onFinish: () => {
            form.reset('name', 'city')
        },
    })
};

const title = 'База'
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
                                        label="Производствена База *"
                                        hint="Име на Производствена База"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />
                                    <q-select
                                        v-model="form.city"
                                        :options="cities"
                                        option-label="name"
                                        label="Избери населено място"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.city_id"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Производствени Бази"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('factories.index'))"
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
