<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
})

const submit = () => {
    form.post(route('roles.store'), {
        onFinish: () => form.reset('name'),
    })
};

const reset = () => {
    form.reset('name')
}

const rolesIndex = () => { router.get(route('roles.index')) }
</script>

<template>

    <Head title="Създаване на роля"></Head>

    <AdminLayout>
        <q-page class="q-pa-md flex flex-col">
            <div class="flex justify-between mb-2">
                <h5>Създаване на роля</h5>
                <q-btn
                    color="primary"
                    label="Управление на роли"
                    @click="rolesIndex"
                />
            </div>
            <div class="flex flex-grow justify-center items-center">
                <q-card
                    class="q-pa-md"
                    style="width: 400px;"
                >
                    <q-form
                        @submit="submit"
                        @reset="reset"
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
    </AdminLayout>
</template>
