<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import VueMultiselect from 'vue-multiselect'

defineProps({
    permissions: Array
})

const form = useForm({
    name: '',
    permissions: []
})

const onSubmit = () => {
    form.post(route('roles.store'), {
        onFinish: () => form.reset('name'),
    })
};

const onReset = () => {
    form.reset('name')
}
</script>

<template>

    <Head title="Нова Роля"></Head>

    <DefaultLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Роли"
                        icon="mdi-menu-left"
                        @click="router.get(route('roles.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Нова Роля</h5>
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
                            label="Роля *"
                            hint="Име на ролята"
                            autofocus
                            :error="form.hasErrors"
                            :error-message="form.errors.name"
                        />

                        <div class="q-my-lg">
                            <VueMultiselect
                                v-model="form.permissions"
                                :options="permissions"
                                :multiple="true"
                                :close-on-select="true"
                                placeholder="Добави права"
                                label="name"
                                track-by="name"
                            />
                        </div>

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

<style src="vue-multiselect/dist/vue-multiselect.css"></style>