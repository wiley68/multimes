<script setup>
import { computed } from 'vue';


const props = defineProps({
    uproduction: {
        type: Object,
        required: true
    },
})

const siloPurcent = computed(() => {
    return parseFloat((parseFloat(props.uproduction.uhall.silo.stock) / parseFloat(props.uproduction.uhall.silo.maxqt)).toFixed(2))
})
const siloPurcentLabel = `${(siloPurcent.value * 100).toFixed(2)}%`
</script>

<template>
    <div class="col text-h4 q-mr-md">
        <q-card class="my-card full-height column">
            <q-card-section class="bg-primary text-white">
                <div class="text-h6 text-center">Хале</div>
            </q-card-section>
            <q-separator />
            <q-card-section class="col">
                <div class="text-subtitle1"><span class="text-weight-medium">Населено място</span><span
                        class="text-weight-light"
                    >: {{
                        uproduction.uhall.factory.city.name }}</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Производствена база</span><span
                        class="text-weight-light"
                    >: {{
                        uproduction.uhall.factory.name }}</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Хале</span><span
                        class="text-weight-light">: {{
                            uproduction.uhall.name }}</span>
                </div>
            </q-card-section>
            <q-card-actions vertical>

            </q-card-actions>
        </q-card>
    </div>
    <div class="col text-h4 q-mr-md">
        <q-card class="my-card full-height column">
            <q-card-section class="bg-deep-orange text-white">
                <div class="text-h6 text-center">Силоз</div>
            </q-card-section>
            <q-separator />
            <q-card-section class="col">
                <div class="text-subtitle1"><span class="text-weight-medium">Силоз</span><span
                        class="text-weight-light">: {{
                            uproduction.uhall.silo.name }}</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Максимум</span><span
                        class="text-weight-light"
                    >: {{
                        parseFloat(uproduction.uhall.silo.maxqt).toFixed(2) }} кг.</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Продукт</span><span
                        class="text-weight-light"
                    >: {{ uproduction.uhall.silo.product ? `[${uproduction.uhall.silo.product.nomenklature}]
                        ${uproduction.uhall.silo.product.name}` : '' }}</span>
                </div>
                <div
                    v-if="uproduction.uhall.silo.product"
                    class="text-subtitle2"
                ><span class="text-weight-light">{{ uproduction.uhall.silo.product.description }}</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Наличност</span><span
                        class="text-weight-light"
                    >: {{
                        parseFloat(uproduction.uhall.silo.stock).toFixed(2) }} кг.</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Цена</span><span
                        class="text-weight-light">: {{
                            parseFloat(uproduction.uhall.silo.price).toFixed(2) }} лв.</span>
                </div>
                <div class="text-subtitle1"><span class="text-weight-medium">Процент запълване</span><span
                        class="text-weight-light"
                    >: {{ siloPurcentLabel }}</span>
                </div>
            </q-card-section>
            <q-card-actions vertical>

            </q-card-actions>
        </q-card>
    </div>
    <div class="col text-h4 q-mr-md">
        <q-card class="my-card full-height column">
            <q-card-section class="bg-secondary text-white">
                <div class="text-h6 text-center">Производствен Процес</div>
            </q-card-section>
            <q-separator />
            <q-card-section class="col">

            </q-card-section>
            <q-card-actions vertical>

            </q-card-actions>
        </q-card>
    </div>
</template>