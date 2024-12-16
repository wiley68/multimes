<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue';

const props = defineProps({
    factories: Array,
    silos: Array,
})

const form = useForm({
    name: '',
    factory: null,
    silo: null,
})
const silosFactory = ref([])

const onSubmit = () => {
    form.post(route('uhalls.store'), {
        onFinish: () => form.reset('name', 'factory', 'silo'),
    })
};

const onReset = () => {
    form.reset('name', 'factory', 'silo')
}

watch(
    () => form.factory,
    (newValue, oldValue) => {
        if (newValue && newValue.id !== oldValue?.id) {
            form.silo = null
            silosFactory.value = props.silos.filter(silo => silo.factory.id === newValue.id)
        }
    }
)

const title = 'Ново Хале угояване'
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout :title="title">
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

                                    <q-select
                                        v-model="form.silo"
                                        :options="silosFactory"
                                        option-label="name"
                                        label="Избери Силоз"
                                        :error="form.hasErrors"
                                        :error-message="form.errors.silo"
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
                        label="Халета"
                        icon="mdi-menu-left"
                        @click="router.get(route('uhalls.index'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
