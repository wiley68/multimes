<script setup></script>

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
                <q-btn @click.prevent="router.get(route('uproductions.loading', uproduction.id))">Зареди прасета</q-btn>
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
                    @click.prevent="router.get(route('silos.loading', { silo: silo.id, from: 'uproductions', from_id: uproduction.id }))"
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