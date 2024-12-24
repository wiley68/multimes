<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue';

const props = defineProps({
    uhall: {
        type: Object,
        required: true
    },
    factories: Array,
    silos: Array,
})

const form = useForm({
    name: props.uhall?.name,
    factory: props.uhall?.factory,
    silo: props.uhall?.silo,
})
const silosFactory = ref(props.silos.filter(silo => silo.factory.id === props.uhall?.factory.id))

const onSubmit = () => {
    form.put(route('uhalls.update', props.uhall.id), {
        onFinish: () => {
            form.reset('name', 'factory', 'silo')
        },
    })
};

watch(
    () => form.factory,
    (newValue, oldValue) => {
        if (newValue && newValue.id !== oldValue?.id) {
            form.silo = null
            silosFactory.value = props.silos.filter(silo => silo.factory.id === newValue.id)
        }
    }
)

const title = 'Хале за угояване'
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
                                        label="Хале *"
                                        hint="Име на Халето"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />

                                    <q-select
                                        v-model="form.factory"
                                        :options="factories"
                                        option-label="name"
                                        label="Избери база"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.factory_id"
                                    />

                                    <q-select
                                        v-model="form.silo"
                                        :options="silosFactory"
                                        option-label="name"
                                        label="Избери Силоз"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.silo"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Халета"
                        flat
                        icon="mdi-menu-left"
                        @click="router.get(route('uhalls.index'))"
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
