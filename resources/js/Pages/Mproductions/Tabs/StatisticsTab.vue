<script setup>
import { computed } from 'vue';

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
})

const totalDecrements = computed(() => {
    return props.mdecrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
})

const totalIncrements = computed(() => {
    return props.mincrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
})

const totalResult = computed(() => {
    return (totalIncrements.value - totalDecrements.value).toFixed(2)
})
</script>

<template>
    <q-card class="my-card full-height column">
        <q-card-section class="col">
            <div class="text-subtitle1">
                <span class="text-weight-medium">Общо разходи за процеса</span>
                <span class="text-weight-light">: {{ totalDecrements }} лв.</span>
            </div>
            <div class="text-subtitle1">
                <span class="text-weight-medium">Общо приходи от процеса</span>
                <span class="text-weight-light">: {{ totalIncrements }} лв.</span>
            </div>
            <div class="text-h6">
                <span
                    class="text-weight-bold"
                    :class="totalResult >= 0 ? 'text-green' : 'text-red'"
                >Печалба/Загуба от процеса</span>
                <span
                    class="text-weight-bold"
                    :class="totalResult >= 0 ? 'text-green' : 'text-red'"
                >: {{ totalResult }} лв.</span>
            </div>
            <div class="text-subtitle1">
                <span class="text-weight-medium">Текущ брой прасета [{{ mproduction.product?.nomenklature }} {{
                    mproduction.product?.name }}]</span>
                <span class="text-weight-light">: {{ mproduction.stock }} {{ mproduction.product?.me }}</span>
            </div>
            <div class="text-subtitle1">
                <span class="text-weight-medium">Текуща цена [{{ mproduction.product?.nomenklature }} {{
                    mproduction.product?.name }}]</span>
                <span class="text-weight-light">: {{ mproduction.price }} лв.</span>
            </div>
        </q-card-section>
    </q-card>
</template>