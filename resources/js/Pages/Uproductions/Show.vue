<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import moment from 'moment'
import InfoTab from './Tabs/InfoTab.vue'
import DataTab from './Tabs/DataTab.vue'
import ExpensesTab from './Tabs/ExpensesTab.vue'
import RevenueTab from './Tabs/RevenueTab.vue'
import StatisticsTab from './Tabs/StatisticsTab.vue'

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
    silo: Object
})

const getDaysBetweenTodayAndDate = (targetDate) => {
    const today = moment().startOf('day')
    const target = moment(targetDate).startOf('day')
    return today.diff(target, 'days')
}

const tab = ref('info')

const productionPurcent = computed(() => {
    const days = getDaysBetweenTodayAndDate(props.uproduction.created_at)
    return parseFloat((days / parseFloat(props.uproduction.production_days)).toFixed(2))
})
const productionPurcentLabel = `${(productionPurcent.value * 100).toFixed(2)}%`
const siloPurcent = computed(() => {
    return parseFloat((parseFloat(props.silo.stock) / parseFloat(props.silo.maxqt)).toFixed(2))
})
const siloPurcentLabel = `${(siloPurcent.value * 100).toFixed(2)}%`

const columns = [
    { name: 'name', required: true, label: 'name', align: 'left', field: row => row.name, format: val => `${val}`, },
    { name: 'value', align: 'center', label: 'value', field: 'value', },
]

const rows = [
    {
        name: 'Текущ брой прасета [бр]',
        value: props.uproduction.stock,
    },
    {
        name: 'Състояние',
        value: props.uproduction.status === 1 ? 'Активен' : 'Приключен',
    },
    {
        name: 'Очакван брой дни за провеждане на процеса',
        value: props.uproduction.production_days,
    },
    {
        name: 'Стартиран на',
        value: moment(props.uproduction.created_at).format('DD.MM.YY HH:mm'),
    },
    {
        name: 'Брой дни в процес',
        value: getDaysBetweenTodayAndDate(props.uproduction.created_at),
    },
    {
        name: 'Оставащи дни до края на процеса',
        value: props.uproduction.production_days - getDaysBetweenTodayAndDate(props.uproduction.created_at),
    },
    {
        name: 'Процент на завършеност на процеса',
        value: productionPurcentLabel,
    },
]

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
                            <InfoTab></InfoTab>
                        </q-tab-panel>

                        <q-tab-panel name="data">
                            <DataTab></DataTab>
                        </q-tab-panel>

                        <q-tab-panel name="expenses">
                            <ExpensesTab></ExpensesTab>
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