<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: ''
})

const onSubmit = () => {
    form.post(route('cities.store'), {
        onFinish: () => form.reset('name'),
    })
};

const onReset = () => {
    form.reset('name')
}
</script>

<template>

    <Head title="Ново Населено място"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Населени места"
                        icon="mdi-menu-left"
                        @click="router.get(route('cities.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Ново Населено място</h5>
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
                            label="Населено място *"
                            hint="Име на населеното място"
                            autofocus
                            :error="form.hasErrors"
                            :error-message="form.errors.name"
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