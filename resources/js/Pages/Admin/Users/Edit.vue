<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { onMounted, watch } from 'vue';
import VueMultiselect from 'vue-multiselect'

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    roles: Array,
    permissions: Array
})

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    password: '',
    roles: [],
    permissions: []
})

const onSubmit = () => {
    form.put(route('users.update', props.user.id), {
        onFinish: () => form.reset('name', 'email', 'password', 'password_confirmation'),
    })
};

const onReset = () => {
    form.reset('name', 'email', 'password', 'password_confirmation')
}

onMounted(() => {
    form.roles = props.user.roles
    form.permissions = props.user.permissions
})

watch(
    () => props.user,
    () => {
        form.roles = props.user.roles
        form.permissions = props.user.permissions
    }
)
</script>

<template>

    <Head title="Редакция на потребител"></Head>

    <AdminLayout>
        <q-page class="q-pa-md column">
            <div class="row items-center justify-between">
                <div class="col row items-center">
                    <q-btn
                        color="primary"
                        label="Потребители"
                        icon="chevron_left"
                        @click="router.get(route('users.index'))"
                    />
                </div>
                <h5 class="col row justify-center items-center">Редакция на потребител</h5>
                <div class="col row justify-end items-center"></div>
            </div>
            <div class="column flex-grow flex-center">
                <q-card
                    class="q-pa-md"
                    style="width: 600px;"
                >
                    <q-form
                        @submit.prevent="onSubmit"
                        @reset="onReset"
                        class="q-gutter-md"
                    >
                        <q-input
                            v-model="form.name"
                            label="Потребител *"
                            hint="Имена на потребителя"
                            :error="form.hasErrors"
                            :error-message="form.errors.name"
                        />

                        <q-input
                            v-model="form.email"
                            label="Имейл *"
                            hint="Имейл на потребителя"
                            :error="form.hasErrors"
                            :error-message="form.errors.email"
                        />

                        <q-input
                            v-model="form.password"
                            type="password"
                            label="Нова парола"
                            hint="Нова парола на потребителя (не е задължителна)"
                            :error="form.hasErrors"
                            :error-message="form.errors.password"
                        />

                        <div class="q-my-lg">
                            <VueMultiselect
                                v-model="form.roles"
                                :options="roles"
                                :multiple="true"
                                :close-on-select="true"
                                placeholder="Добави роли"
                                label="name"
                                track-by="name"
                            />
                        </div>

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

<style src="vue-multiselect/dist/vue-multiselect.css"></style>