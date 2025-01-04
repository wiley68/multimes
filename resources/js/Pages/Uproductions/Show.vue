<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import ActionsTab from './Tabs/ActionsTab.vue'
import InfoTab from './Tabs/InfoTab.vue'
import DecrementsTab from './Tabs/DecrementsTab.vue'
import IncrementsTab from './Tabs/IncrementsTab.vue'
import StatisticsTab from './Tabs/StatisticsTab.vue'
import { useQuasar } from 'quasar'
import { onMounted, ref } from 'vue'

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
    udecrements: Array,
})

const tab = ref('actions')

const $q = useQuasar()
onMounted(() => {
    Object.values(usePage().props.errors).flat().forEach((error) => {
        $q.notify({
            message: error,
            icon: 'mdi-alert-circle-outline',
            type: 'negative',
        });
    });
})

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
                            name="actions"
                            icon="mdi-gesture-tap-button"
                            label="Управление"
                            :alert="false"
                            alert-icon="alarm_on"
                        />
                        <q-tab
                            name="info"
                            icon="mdi-information-outline"
                            label="Информация"
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
                            name="increments"
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
                            name="actions"
                            class="row"
                        >
                            <ActionsTab :uproduction="uproduction"></ActionsTab>
                        </q-tab-panel>

                        <q-tab-panel
                            name="info"
                            class="row"
                        >
                            <InfoTab :uproduction="uproduction"></InfoTab>
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

                        <q-tab-panel name="increments">
                            <IncrementsTab></IncrementsTab>
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