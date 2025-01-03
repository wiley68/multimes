<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head } from '@inertiajs/vue3'
import InfoTab from './Tabs/InfoTab.vue'
import DataTab from './Tabs/DataTab.vue'
import DecrementsTab from './Tabs/DecrementsTab.vue'
import RevenueTab from './Tabs/RevenueTab.vue'
import StatisticsTab from './Tabs/StatisticsTab.vue'
import { ref } from 'vue'

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
    silo: Object,
    udecrements: Array,
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
                            name="decrements"
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
                    <q-tab-panels
                        class="col full-height"
                        v-model="tab"
                        animated
                        transition-prev="slide-down"
                        transition-next="slide-up"
                    >
                        <q-tab-panel
                            name="info"
                            class="row"
                        >
                            <InfoTab
                                :uproduction="uproduction"
                                :silo="silo"
                            ></InfoTab>
                        </q-tab-panel>

                        <q-tab-panel name="data">
                            <DataTab></DataTab>
                        </q-tab-panel>

                        <q-tab-panel
                            name="decrements"
                            class="q-pa-none"
                        >
                            <DecrementsTab
                                :uproduction="uproduction"
                                :udecrements="udecrements"
                            ></DecrementsTab>
                        </q-tab-panel>

                        <q-tab-panel name="revenue">
                            <RevenueTab></RevenueTab>
                        </q-tab-panel>

                        <q-tab-panel name="statistics">
                            <StatisticsTab></StatisticsTab>
                        </q-tab-panel>
                    </q-tab-panels>
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>