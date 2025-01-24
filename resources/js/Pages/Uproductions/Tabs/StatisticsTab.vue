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

const totalDecrements = computed(() => {
    return props.udecrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
})

const totalIncrements = computed(() => {
    return props.uincrements
        .filter(item => item.status === 1)
        .reduce((total, item) => total + item.quantity * item.price, 0)
        .toFixed(2);
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
            <div class="text-subtitle1">
                <span class="text-weight-medium">Текущ брой прасета [{{ uproduction.product?.nomenklature }} {{
                    uproduction.product?.name }}]</span>
                <span class="text-weight-light">: {{ uproduction.stock }} {{ uproduction.product?.me }}</span>
            </div>
            <div class="text-subtitle1">
                <span class="text-weight-medium">Текуща цена [{{ uproduction.product?.nomenklature }} {{
                    uproduction.product?.name }}]</span>
                <span class="text-weight-light">: {{ uproduction.price }} лв.</span>
            </div>
        </q-card-section>
    </q-card>
</template>