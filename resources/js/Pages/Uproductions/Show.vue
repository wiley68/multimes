<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    }
})

const title = `Производствен процес Угояване №${props.uproduction.id}`
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout :title="title">
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
                <h5 class="col row justify-center items-center">{{ `Производствен процес Угояване №${uproduction.id}` }}
                </h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">

            </div>
        </q-page>
    </DefaultLayout>
</template>