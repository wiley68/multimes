<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

defineProps({
    cities: Array
})

const form = useForm({
    name: '',
    city: null
})

const onSubmit = () => {
    form.post(route('factories.store'), {
        onFinish: () => form.reset('name', 'city'),
    })
};

const onReset = () => {
    form.reset('name', 'city')
}

const title = 'Нова База'
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
                                        label="Производствена База *"
                                        hint="Име на Производствената Базата"
                                        autofocus
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
                        label="Производствени Бази"
                        icon="mdi-menu-left"
                        @click="router.get(route('factories.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
