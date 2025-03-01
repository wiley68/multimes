<script setup>
import { computed } from 'vue'

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    },
    mdecrements: {
        type: Array,
        required: true
    },
    mincrements: {
        type: Array,
        required: true
    },
    mhallInfo: {
        type: Object,
        required: true
    },
})

const totalDecrementsMaterial = computed(() => {
    return parseInt(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product)
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalDecrementsMaterialWeight = computed(() => {
    return parseFloat(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product)
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalDecrementsFuraz = computed(() => {
    return parseInt(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.furaz)
        .reduce((total, item) => total + 1, 0)
    )
})

const totalDecrementsFurazWeight = computed(() => {
    return parseFloat(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.furaz)
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsSell = computed(() => {
    return parseInt(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Продажба')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsSellWeight = computed(() => {
    return parseFloat(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Продажба')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalIncementsPrehvarliane = computed(() => {
    return parseInt(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Прехвърляне')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsPrehvarlianeWeight = computed(() => {
    return parseFloat(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Прехвърляне')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalIncementsUmreli = computed(() => {
    return parseInt(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Умрели')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsUmreliWeight = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Умрели')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalIncementsWeight = computed(() => {
    return parseFloat(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product)
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalDecrementsLoadingPrice = computed(() => {
    return parseFloat(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product)
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalDecrementsFurazPrice = computed(() => {
    return parseFloat(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.furaz)
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalDecrementsDrugiPrice = computed(() => {
    return parseFloat(props.mdecrements
        .filter(item => item.status === 1 && item.product?.type === 'Обща употреба')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalIncrementsSellPrice = computed(() => {
    return parseFloat(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Продажба')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalIncrementsPrehvarlianePrice = computed(() => {
    return parseFloat(props.mincrements
        .filter(item => item.status === 1 && item.product?.type === props.mhallInfo.product && item.type === 'Прехвърляне')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalDecrements = computed(() => {
    return parseFloat(props.mdecrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalIncrements = computed(() => {
    return parseFloat(props.mincrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalResult = computed(() => {
    return parseFloat(totalIncrements.value - totalDecrements.value)
})

const totalPrirast = computed(() => {
    return parseFloat(totalIncementsWeight.value - totalDecrementsMaterialWeight.value)
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
                    <span class="text-weight-medium">{{ `Брой вкарани
                        ${mproduction.product ? mproduction.product.name : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalDecrementsMaterial }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло вкарани ${mproduction.product ? mproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalDecrementsMaterialWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">
                        {{ `Брой зареждания на ${mhallInfo.furaz}: ` }}
                    </span>
                    <span class="text-weight-light">{{ totalDecrementsFuraz }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">
                        {{ `Тегло зареден ${mhallInfo.furaz}: ` }}
                    </span>
                    <span class="text-weight-light">{{ totalDecrementsFurazWeight.toFixed(2) }} кг</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой продадени ${mproduction.product ? mproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsSell }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло продадени ${mproduction.product ?
                        mproduction.product.name : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsSellWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой прехвърлени ${mproduction.product ?
                        mproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsPrehvarliane }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло прехвърлени ${mproduction.product ?
                        mproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsPrehvarlianeWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой умрели ${mproduction.product ? mproduction.product.name :
                        'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsUmreli }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло умрели ${mproduction.product ? mproduction.product.name :
                        'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsPrehvarlianeWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Общо тегло изкарани ${mproduction.product ?
                        mproduction.product.name : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsWeight.toFixed(2) }} кг</span>
                </div>
                <q-separator />
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Прираст тегло за целия процес: </span>
                    <span class="text-weight-light">{{ totalPrirast.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Съотношение кг фураж за 1кг живо тегло: </span>
                    <span class="text-weight-light">{{ totalPrirast !== 0.00 ? (totalDecrementsFurazWeight /
                        totalPrirast).toFixed(2) : '0.00'
                        }}</span>
                </div>
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Смъртност количество умрели прасета: </span>
                    <span class="text-weight-light">{{ totalIncementsUmreli }} бр</span>
                </div>
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Смъртност процент умрели спрямо заредени: </span>
                    <span class="text-weight-light">{{ totalDecrementsMaterial !== 0.00 ? ((totalIncementsUmreli /
                        totalDecrementsMaterial) *
                        100).toFixed(2) : '0.00' }} %</span>
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
                    <span class="text-weight-medium">{{ `Текущ брой ${mproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light"> {{ mproduction.stock }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Текуща цена ${mproduction.product?.name}: ` }}</span>
                    <span class="text-weight-light">{{ mproduction.price }} лв</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Разходи за вкарване на прасета</span>
                    <span class="text-weight-light">: {{ totalDecrementsLoadingPrice.toFixed(2) }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Разходи за зареждане на фураж</span>
                    <span class="text-weight-light">: {{ totalDecrementsFurazPrice.toFixed(2) }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Разходи други</span>
                    <span class="text-weight-light">: {{ totalDecrementsDrugiPrice.toFixed(2) }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Общо разходи за процеса</span>
                    <span class="text-weight-light">: {{ totalDecrements.toFixed(2) }} лв.</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Приходи от продажби на прасета</span>
                    <span class="text-weight-light">: {{ totalIncrementsSellPrice.toFixed(2) }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Приходи от прехвърляне на прасета</span>
                    <span class="text-weight-light">: {{ totalIncrementsPrehvarlianePrice.toFixed(2) }} лв</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">Общо приходи от процеса</span>
                    <span class="text-weight-light">: {{ totalIncrements.toFixed(2) }} лв.</span>
                </div>
                <q-separator />
                <div class="text-subtitle1 text-accent">
                    <span class="text-weight-medium">Печалба/Загуба от процеса</span>
                    <span class="text-weight-light">: {{ totalResult.toFixed(2) }} лв.</span>
                </div>
            </q-card-section>
        </q-card>
    </div>
</template>
