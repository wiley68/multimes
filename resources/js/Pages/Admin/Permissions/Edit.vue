<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    permission: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.permission.name,
})

const onSubmit = () => {
    form.put(route('permissions.update', props.permission.id), {
        onFinish: () => form.reset('name'),
    })
};

const onReset = () => {
    form.reset('name')
}
</script>

<template>

    <Head title="Редакция на право"></Head>

    <AdminLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Права"
                        icon="chevron_left"
                        @click="router.get(route('permissions.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Редакция на право</h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">
                <q-card
                    class="q-pa-md"
                    style="width: 400px;"
                >
                    <q-form
                        @submit.prevent="onSubmit"
                        @reset="onReset"
                        class="q-gutter-md"
                    >
                        <q-input
                            v-model="form.name"
                            label="Право *"
                            hint="Име на правото"
                            autofocus
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
    </AdminLayout>
</template>
