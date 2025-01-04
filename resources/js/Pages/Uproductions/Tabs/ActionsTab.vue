<script setup>
import moment from 'moment'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
})

const getDaysBetweenTodayAndDate = (targetDate) => {
    const today = moment().startOf('day')
    const target = moment(targetDate).startOf('day')
    return today.diff(target, 'days')
}

const productionPurcent = computed(() => {
    const days = getDaysBetweenTodayAndDate(props.uproduction.created_at)
    return parseFloat((days / parseFloat(props.uproduction.production_days)).toFixed(2))
})
const productionPurcentLabel = `${(productionPurcent.value * 100).toFixed(2)}%`
const siloPurcent = computed(() => {
    return parseFloat((parseFloat(props.uproduction.uhall.silo.stock) / parseFloat(props.uproduction.uhall.silo.maxqt)).toFixed(2))
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
</script>

<template>
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
                    @click.prevent="router.get(route('uproductions.loading', { uproduction: uproduction.id, from: 'uproduction' }))"
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
                <div class="text-h6 text-center">{{ uproduction.uhall.silo.name }}</div>
            </q-card-section>
            <q-separator />
            <q-card-section>
                <div class="text-h5">[{{ uproduction.uhall.silo.product?.nomenklature }}] {{
                    uproduction.uhall.silo.product?.name
                    }}</div>
                <div class="text-caption">{{ uproduction.uhall.silo.product?.description }}</div>
            </q-card-section>
            <q-separator />
            <q-card-section class="col">
                <div class="text-subtitle1">Максимум: {{ parseFloat(uproduction.uhall.silo.maxqt).toFixed(2) }} {{
                    uproduction.uhall.silo.product?.me }}
                </div>
                <div class="text-subtitle1">Текущо: {{ parseFloat(uproduction.uhall.silo.stock).toFixed(2) }} {{
                    uproduction.uhall.silo.product?.me }}</div>
                <div class="text-subtitle1 text-accent">Процент запълване: {{ siloPurcentLabel
                    }}</div>
            </q-card-section>
            <q-card-actions vertical>
                <q-btn
                    @click.prevent="router.get(route('silos.loading', { silo: uproduction.uhall.silo.id, from: 'uproductions', from_id: uproduction.id }))"
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
</template>