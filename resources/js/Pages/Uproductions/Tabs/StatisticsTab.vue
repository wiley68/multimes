<script setup>
import { computed } from 'vue';

const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
    udecrements: {
        type: Array,
        required: true
    },
    uincrements: {
        type: Array,
        required: true
    },
})

const totalDecrementsMaterial = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.quantity, 0)
})

const totalDecrementsMaterialWeight = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.weight, 0)
        .toFixed(2)
})

const totalDecrementsFuraz = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Фураж угояване')
        .reduce((total, item) => total + 1, 0)
})

const totalDecrementsFurazWeight = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Фураж угояване')
        .reduce((total, item) => total + item.quantity, 0)
        .toFixed(2)
})

const totalIncementsSell = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Продажба')
        .reduce((total, item) => total + item.quantity, 0)
})

const totalIncementsSellWeight = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Продажба')
        .reduce((total, item) => total + item.weight, 0)
        .toFixed(2)
})

const totalIncementsRemont = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Ремонт')
        .reduce((total, item) => total + item.quantity, 0)
})

const totalIncementsRemontWeight = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Ремонт')
        .reduce((total, item) => total + item.weight, 0)
        .toFixed(2)
})

const totalIncementsUmreli = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Умрели')
        .reduce((total, item) => total + item.quantity, 0)
})

const totalIncementsUmreliWeight = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Умрели')
        .reduce((total, item) => total + item.weight, 0)
        .toFixed(2)
})

const totalIncementsWeight = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.weight, 0)
        .toFixed(2)
})

const totalDecrementsLoadingPrice = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalDecrementsFurazPrice = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Фураж угояване')
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalDecrementsDrugiPrice = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Обща употреба')
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalIncrementsSellPrice = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Продажба')
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalIncrementsRemontPrice = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Ремонт')
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalDecrements = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalIncrements = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2)
})

const totalResult = computed(() => {
    return (totalIncrements.value - totalDecrements.value).toFixed(2)
})
</script>

<template>
    <div class="col text-h4 q-mr-md">
        <q-card class="my-card full-height column">
            <q-card-section class="bg-primary text-white">
                <div class="text-h6 text-center">Материални потоци</div>
            </q-card-section>
            <q-separator />
            <q-card-section class="col">
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой вкарани ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalDecrementsMaterial }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло вкарани ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalDecrementsMaterialWeight }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">
                        {{ `Брой зареждания на Фураж угояване: ` }}
                    </span>
                    <span class="text-weight-light">{{ totalDecrementsFuraz }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">
                        {{ `Тегло зареден Фураж угояване: ` }}
                    </span>
                    <span class="text-weight-light">{{ totalDecrementsFurazWeight }} кг</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой продадени ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsSell }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло продадени ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsSellWeight }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой ремонтни ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsRemont }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло ремонтни ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsRemontWeight }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой умрели ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsUmreli }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло умрели ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsUmreliWeight }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Общо тегло изкарани ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsWeight }} кг</span>
                </div>
                <q-separator />
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Прираст тегло за целия процес: </span>
                    <span class="text-weight-light">{{ (totalIncementsWeight - totalDecrementsMaterialWeight).toFixed(2)
                        }} кг</span>
                </div>
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Съотношение кг фураж за 1кг живо тегло: </span>
                    <span class="text-weight-light">{{ (totalDecrementsFurazWeight / (totalIncementsWeight -
                        totalDecrementsMaterialWeight)).toFixed(2)
                        }}</span>
                </div>
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Смъртност количество умрели прасета: </span>
                    <span class="text-weight-light">{{ totalIncementsUmreli }} бр</span>
                </div>
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Смъртност процент умрели спрямо заредени: </span>
                    <span class="text-weight-light">{{ ((totalIncementsUmreli / totalDecrementsMaterial) *
                        100).toFixed(2) }} %</span>
                </div>
            </q-card-section>
        </q-card>
    </div>

    <div class="col text-h4 q-mr-md">
        <q-card class="my-card full-height column">
            <q-card-section class="bg-secondary text-white">
                <div class="text-h6 text-center">Парични потоци</div>
            </q-card-section>
            <q-separator />
            <q-card-section class="col">
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Текущ брой ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light"> {{ uproduction.stock }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Текуща цена ${uproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ uproduction.price }} лв</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Разходи за вкарване на прасета</span>
                    <span class="text-weight-light">: {{ totalDecrementsLoadingPrice }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Разходи за зареждане на фураж</span>
                    <span class="text-weight-light">: {{ totalDecrementsFurazPrice }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Разходи други</span>
                    <span class="text-weight-light">: {{ totalDecrementsDrugiPrice }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Общо разходи за процеса</span>
                    <span class="text-weight-light">: {{ totalDecrements }} лв.</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Приходи от продажби на прасета</span>
                    <span class="text-weight-light">: {{ totalIncrementsSellPrice }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Приходи от ремонтни прасета</span>
                    <span class="text-weight-light">: {{ totalIncrementsRemontPrice }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Общо приходи от процеса</span>
                    <span class="text-weight-light">: {{ totalIncrements }} лв.</span>
                </div>
                <q-separator />
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Печалба/Загуба от процеса</span>
                    <span class="text-weight-light">: {{ totalResult }} лв.</span>
                </div>
            </q-card-section>
        </q-card>
    </div>
</template>