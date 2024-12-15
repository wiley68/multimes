<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue';

const props = defineProps({
    mhall: {
        type: Object,
        required: true
    },
    factories: Array,
    silos: Array
})

const form = useForm({
    name: props.mhall?.name,
    factory: props.mhall?.factory,
    silo: props.mhall?.silo,
})
const silosFactory = ref(props.silos.filter(silo => silo.factory.id === props.mhall?.factory.id))

const onSubmit = () => {
    form.put(route('mhalls.update', props.mhall.id), {
        onFinish: () => {
            form.reset('name', 'factory', 'silo')
        },
    })
};

const onReset = () => {
    form.reset('name', 'factory', 'silo')
}

watch(
    () => form.factory,
    (newValue, oldValue) => {
        if (newValue && newValue.id !== oldValue.id) {
            form.silo = null
            silosFactory.value = props.silos.filter(silo => silo.factory.id === newValue.id)
        }
    }
)
</script>

<template>

    <Head title="Промяна на Хале майки"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Халета"
                        icon="mdi-menu-left"
                        @click="router.get(route('mhalls.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Промяна на Хале майки</h5>
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
