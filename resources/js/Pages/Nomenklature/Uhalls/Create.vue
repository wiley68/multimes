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
    form.post(route('uhalls.store'), {
        onFinish: () => form.reset('name', 'factory'),
    })
};

const onReset = () => {
    form.reset('name', 'factory')
}
</script>

<template>

    <Head title="Ново Хале угояване"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Халета"
                        icon="mdi-menu-left"
                        @click="router.get(route('uhalls.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Ново Хале угояване</h5>
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
                            label="Хале *"
                            hint="Име на Халето"
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
                            :error-message="form.errors.factory_id"
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
    </DefaultLayout>
</template>
