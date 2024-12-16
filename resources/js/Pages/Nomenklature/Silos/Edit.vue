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

const onReset = () => {
    form.reset('name', 'factory')
}

const title = 'Промяна на Силоз'
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout :title="title">
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Силози"
                        icon="mdi-menu-left"
                        @click="router.get(route('silos.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Промяна на Силоз</h5>
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

                        <div>
                            <q-btn
                                label="Промени"
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
