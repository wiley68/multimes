<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import { useQuasar } from 'quasar'
import ActionsTab from './Tabs/ActionsTab.vue'
import InfoTab from './Tabs/InfoTab.vue'
import StatisticsTab from './Tabs/StatisticsTab.vue'
import DecrementsTab from './Tabs/DecrementsTab.vue'
import IncrementsTab from './Tabs/IncrementsTab.vue'

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    },
    mdecrements: Array,
    mincrements: Array,
    tab: {
        type: String,
        required: true
    },
})

const tabName = ref('actions')

const $q = useQuasar()
onMounted(() => {
    tabName.value = props.tab
    Object.values(usePage().props.errors).flat().forEach((error) => {
        $q.notify({
            message: error,
            icon: 'mdi-alert-circle-outline',
            type: 'negative',
        });
    });
})

const mhallInfo = () => {
    switch (props.mproduction.mhall.type) {
        case 'Ремонтни':
            return {
                product: 'Прасета ремонтни',
                furaz: 'Фураж ремонтни',
            }
        case 'Заплождане':
            return {
                product: 'Прасета заплождане',
                furaz: 'Фураж бременни',
            }
        case 'Условна бременност':
            return {
                product: 'Прасета условна бременност',
                furaz: 'Фураж бременни',
            }
        case 'Бременност':
            return {
                product: 'Прасета бременност',
                furaz: 'Фураж бременни',
            }
        case 'Родилно':
            return {
                product: 'Прасета родилно',
                furaz: 'Фураж кърмачки',
            }
        case 'Подрастване':
            return {
                product: 'Прасета подрастване',
                furaz: 'Фураж угояване',
            }
        default:
            return {
                product: 'Прасета',
                furaz: 'Фураж',
            }
    }
}

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
                        v-model="tabName"
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
                        v-model="tabName"
                        animated
                        transition-prev="slide-down"
                        transition-next="slide-up"
                    >
                        <q-tab-panel
                            name="actions"
                            class="row"
                        >
                            <ActionsTab
                                :mproduction="mproduction"
                                :mhall-info="mhallInfo()"
                            ></ActionsTab>
                        </q-tab-panel>

                        <q-tab-panel
                            name="info"
                            class="row"
                        >
                            <InfoTab
                                :mproduction="mproduction"
                                :mdecrements="mdecrements"
                                :mincrements="mincrements"
                            ></InfoTab>
                        </q-tab-panel>

                        <q-tab-panel
                            name="decrements"
                            class="q-pa-none"
                        >
                            <DecrementsTab
                                :mproduction="mproduction"
                                :mdecrements="mdecrements"
                            ></DecrementsTab>
                        </q-tab-panel>

                        <q-tab-panel
                            name="increments"
                            class="q-pa-none"
                        >
                            <IncrementsTab
                                :mproduction="mproduction"
                                :mincrements="mincrements"
                            ></IncrementsTab>
                        </q-tab-panel>

                        <q-tab-panel name="statistics">
                            <StatisticsTab
                                :mproduction="mproduction"
                                :mdecrements="mdecrements"
                                :mincrements="mincrements"
                            ></StatisticsTab>
                        </q-tab-panel>
                    </q-tab-panels>
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
