<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import moment from 'moment'

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
                            <div class="col text-h4 q-mr-md">
                                <q-card class="my-card full-height column">
                                    <q-card-section class="bg-secondary text-white">
                                        <div class="text-h6 text-center">Производствен Процес №{{ uproduction.id }}
                                        </div>
                                    </q-card-section>
                                    <q-separator />
                                    <q-card-section>
                                        <div class="text-h5">[{{ uproduction.product?.nomenklature }}] {{
                                            uproduction.product?.name }}
                                        </div>
                                        <div class="text-caption">{{ uproduction.product?.description }}</div>
                                    </q-card-section>
                                    <q-separator />
                                    <q-card-section class="col">
                                        <q-table
                                            hide-header
                                            hide-bottom
                                            flat
                                            bordered
                                            :rows-per-page-options="[10]"
                                            :rows="rows"
                                            :columns="columns"
                                            row-key="name"
                                        />
                                    </q-card-section>
                                    <q-card-actions vertical>
                                        <q-btn
                                            flat
                                            @click.prevent="router.get(route('uproductions.loading', uproduction.id))"
                                        >Зареди прасета</q-btn>
                                    </q-card-actions>
                                    <q-separator />
                                    <q-card-section class="q-pa-xs q-ma-none">
                                        <q-linear-progress
                                            size="100%"
                                            :value="productionPurcent"
                                            color="secondary"
                                        >
                                            <div class="absolute-full flex flex-center">
                                                <q-badge
                                                    color="white"
                                                    text-color="accent"
                                                    :label="productionPurcentLabel"
                                                />
                                            </div>
                                        </q-linear-progress>
                                    </q-card-section>
                                </q-card>
                            </div>
                            <div
                                class="text-h4"
                                style="width: 300px;"
                            >
                                <q-card class="my-card full-height column">
                                    <q-card-section class="bg-deep-orange text-white">
                                        <div class="text-h6 text-center">{{ silo.name }}</div>
                                    </q-card-section>
                                    <q-separator />
                                    <q-card-section>
                                        <div class="text-h5">[{{ silo.product?.nomenklature }}] {{ silo.product?.name
                                            }}</div>
                                        <div class="text-caption">{{ silo.product?.description }}</div>
                                    </q-card-section>
                                    <q-separator />
                                    <q-card-section class="col">
                                        <div class="text-subtitle1">Максимум: {{ parseFloat(silo.maxqt).toFixed(2) }} {{
                                            silo.product?.me }}
                                        </div>
                                        <div class="text-subtitle1">Текущо: {{ parseFloat(silo.stock).toFixed(2) }} {{
                                            silo.product?.me }}</div>
                                        <div class="text-subtitle1 text-accent">Процент запълване: {{ siloPurcentLabel
                                            }}</div>
                                    </q-card-section>
                                    <q-card-actions vertical>
                                        <q-btn
                                            flat
                                            @click.prevent="router.get(route('silos.loading', silo.id))"
                                        >Зареди фураж</q-btn>
                                    </q-card-actions>
                                    <q-separator />
                                    <q-card-section class="q-pa-xs q-ma-none">
                                        <q-linear-progress
                                            size="100%"
                                            :value="siloPurcent"
                                            color="deep-orange"
                                        >
                                            <div class="absolute-full flex flex-center">
                                                <q-badge
                                                    color="white"
                                                    text-color="accent"
                                                    :label="siloPurcentLabel"
                                                />
                                            </div>
                                        </q-linear-progress>
                                    </q-card-section>
                                </q-card>
                            </div>
                        </q-tab-panel>

                        <q-tab-panel name="data">
                            <div class="text-h4">Данни</div>
                        </q-tab-panel>

                        <q-tab-panel name="expenses">
                            <div class="text-h4">Разходи</div>
                        </q-tab-panel>

                        <q-tab-panel name="revenue">
                            <div class="text-h4">Приходи</div>
                        </q-tab-panel>

                        <q-tab-panel name="statistics">
                            <div class="text-h4">Статистика</div>
                        </q-tab-panel>
                    </q-tab-panels>
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>