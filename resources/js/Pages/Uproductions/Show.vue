<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    }
})

const tab = ref('info')

const title = `Хале: ${props.uproduction.uhall.name}, Процес: №${props.uproduction.id}`
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="header-panel">
                    <q-tabs
                        v-model="tab"
                        no-caps
                        inline-label
                        class="bg-grey-1 full-width text-primary"
                        active-color="accent"
                    >
                        <q-tab
                            name="info"
                            icon="mdi-information-outline"
                            label="Информация"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="data"
                            icon="mdi-database-edit-outline"
                            label="Данни"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="expenses"
                            icon="mdi-minus-circle-outline"
                            label="Разходи"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="revenue"
                            icon="mdi-plus-circle-outline"
                            label="Приходи"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="statistics"
                            icon="mdi-chart-box-outline"
                            label="Статистика"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                    </q-tabs>
                </div>

                <div
                    class="col"
                    style="overflow-y: auto;"
                >
                    <div class="row full-height full-width">
                        <div class="col">
                            <q-tab-panels
                                v-model="tab"
                                animated
                                transition-prev="slide-down"
                                transition-next="slide-up"
                            >
                                <q-tab-panel name="info">
                                    <div class="text-h4 q-mb-md">Информация</div>
                                </q-tab-panel>

                                <q-tab-panel name="data">
                                    <div class="text-h4 q-mb-md">Данни</div>
                                </q-tab-panel>

                                <q-tab-panel name="expenses">
                                    <div class="text-h4 q-mb-md">Разходи</div>
                                </q-tab-panel>

                                <q-tab-panel name="revenue">
                                    <div class="text-h4 q-mb-md">Приходи</div>
                                </q-tab-panel>

                                <q-tab-panel name="statistics">
                                    <div class="text-h4 q-mb-md">Статистика</div>
                                </q-tab-panel>
                            </q-tab-panels>
                        </div>
                    </div>
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>