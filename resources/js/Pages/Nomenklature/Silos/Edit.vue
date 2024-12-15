<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    silo: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.silo?.name,
})

const onSubmit = () => {
    form.put(route('silos.update', props.silo.id), {
        onFinish: () => {
            form.reset('name')
        },
    })
};

const onReset = () => {
    form.reset('name')
}
</script>

<template>

    <Head title="Промяна на Силоз"></Head>

    <DefaultLayout>
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
