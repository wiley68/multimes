<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    role: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.role.name,
})

const onSubmit = () => {
    form.put(route('roles.update', props.role.id), {
        onFinish: () => form.reset('name'),
    })
};

const onReset = () => {
    form.reset('name')
}
</script>

<template>

    <Head title="Редакция на роля"></Head>

    <AdminLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <h5>Редакция на роля</h5>
                <q-btn
                    color="primary"
                    label="Управление на роли"
                    @click="router.get(route('roles.index'))"
                />
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
                            label="Роля *"
                            hint="Име на ролята"
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
