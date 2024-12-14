<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    }
})
</script>

<template>

    <Head :title="`Производствен процес Майки №${mproduction.id}`"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Табло"
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">{{ `Производствен процес Майки №${mproduction.id}` }}
                </h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">

            </div>
        </q-page>
    </DefaultLayout>
</template>