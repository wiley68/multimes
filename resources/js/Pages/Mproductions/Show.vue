<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    }
})

const tab = ref('info')

const title = `Хале: ${props.mproduction.mhall.name}, Процес: №${props.mproduction.id}`
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
                            icon="mail"
                            label="Информация"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="data"
                            icon="alarm"
                            label="Данни"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="expenses"
                            icon="movie"
                            label="Разходи"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="revenue"
                            icon="movie"
                            label="Приходи"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="statistics"
                            icon="movie"
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
                                    <div class="text-h4 q-mb-md">Mails</div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                </q-tab-panel>

                                <q-tab-panel name="data">
                                    <div class="text-h4 q-mb-md">Alarms</div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                </q-tab-panel>

                                <q-tab-panel name="expenses">
                                    <div class="text-h4 q-mb-md">Movies</div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                </q-tab-panel>

                                <q-tab-panel name="revenue">
                                    <div class="text-h4 q-mb-md">Movies</div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                </q-tab-panel>

                                <q-tab-panel name="statistics">
                                    <div class="text-h4 q-mb-md">Movies</div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque
                                        magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima
                                        assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                </q-tab-panel>
                            </q-tab-panels>
                        </div>
                    </div>
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
