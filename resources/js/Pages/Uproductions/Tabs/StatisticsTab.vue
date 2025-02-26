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
    return parseInt(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalDecrementsMaterialWeight = computed(() => {
    return parseFloat(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalDecrementsFuraz = computed(() => {
    return parseInt(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Фураж угояване')
        .reduce((total, item) => total + 1, 0)
    )
})

const totalDecrementsFurazWeight = computed(() => {
    return parseFloat(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Фураж угояване')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsSell = computed(() => {
    return parseInt(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Продажба')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsSellWeight = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Продажба')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalIncementsRemont = computed(() => {
    return parseInt(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Ремонт')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsRemontWeight = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Ремонт')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalIncementsUmreli = computed(() => {
    return parseInt(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Умрели')
        .reduce((total, item) => total + item.quantity, 0)
    )
})

const totalIncementsUmreliWeight = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Умрели')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalIncementsWeight = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.weight, 0)
    )
})

const totalDecrementsLoadingPrice = computed(() => {
    return parseFloat(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalDecrementsFurazPrice = computed(() => {
    return parseFloat(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Фураж угояване')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalDecrementsDrugiPrice = computed(() => {
    return parseFloat(props.udecrements
        .filter(item => item.status === 1 && item.product?.type === 'Обща употреба')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalIncrementsSellPrice = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Продажба')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalIncrementsRemontPrice = computed(() => {
    return parseFloat(props.uincrements
        .filter(item => item.status === 1 && item.product?.type === 'Прасета угояване' && item.type === 'Ремонт')
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalDecrements = computed(() => {
    return parseFloat(props.udecrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
    )
})

const totalIncrements = computed(() => {
    return parseFloat(props.uincrements
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
                        ${uproduction.product ? uproduction.product.name : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalDecrementsMaterial }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло вкарани ${uproduction.product ? uproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalDecrementsMaterialWeight.toFixed(2) }} кг</span>
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
                    <span class="text-weight-light">{{ totalDecrementsFurazWeight.toFixed(2) }} кг</span>
                </div>
                <q-separator />
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой продадени ${uproduction.product ? uproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsSell }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло продадени ${uproduction.product ?
                        uproduction.product.name : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsSellWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой ремонтни ${uproduction.product ? uproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsRemont }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло ремонтни ${uproduction.product ? uproduction.product.name
                        : 'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsRemontWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Брой умрели ${uproduction.product ? uproduction.product.name :
                        'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsUmreli }} бр</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Тегло умрели ${uproduction.product ? uproduction.product.name :
                        'прасета'}: ` }}</span>
                    <span class="text-weight-light">{{ totalIncementsUmreliWeight.toFixed(2) }} кг</span>
                </div>
                <div class="text-subtitle1">
                    <span class="text-weight-medium">{{ `Общо тегло изкарани ${uproduction.product ?
                        uproduction.product.name : 'прасета'}: ` }}</span>
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
                    <span class="text-weight-medium">Приходи от ремонтни прасета</span>
                    <span class="text-weight-light">: {{ totalIncrementsRemontPrice.toFixed(2) }} лв</span>
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