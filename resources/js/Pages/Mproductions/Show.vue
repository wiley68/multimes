<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
    mproduction: {
        type: Object,
        required: true
    }
})

const tab = ref('mails')
const splitterModel = ref(10)

const title = `Хале: ${props.mproduction.mhall.name}, Процес: №${props.mproduction.id}`
</script>

<template>

    <Head :title="title"></Head>

    <DefaultLayout
        :title="title"
        icon="mdi-file-document-outline"
    >
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content-tab">
                        <q-splitter
                            v-model="splitterModel"
                            class="splitter-content"
                        >
                            <template v-slot:before>
                                <q-tabs
                                    v-model="tab"
                                    vertical
                                    class="text-teal"
                                >
                                    <q-tab
                                        name="mails"
                                        icon="mail"
                                        label="Mails"
                                    />
                                    <q-tab
                                        name="alarms"
                                        icon="alarm"
                                        label="Alarms"
                                    />
                                    <q-tab
                                        name="movies"
                                        icon="movie"
                                        label="Movies"
                                    />
                                </q-tabs>
                            </template>

                            <template v-slot:after>
                                <q-tab-panels
                                    v-model="tab"
                                    animated
                                    swipeable
                                    vertical
                                    transition-prev="jump-up"
                                    transition-next="jump-up"
                                >
                                    <q-tab-panel name="mails">
                                        <div class="text-h4 q-mb-md">Mails</div>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    </q-tab-panel>

                                    <q-tab-panel name="alarms">
                                        <div class="text-h4 q-mb-md">Alarms</div>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    </q-tab-panel>

                                    <q-tab-panel name="movies">
                                        <div class="text-h4 q-mb-md">Movies</div>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium
                                            cumque magnam odio iure quidem, quod illum numquam possimus obcaecati
                                            commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                                    </q-tab-panel>
                                </q-tab-panels>
                            </template>
                        </q-splitter>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn
                        color="primary"
                        label="Табло"
                        icon="mdi-menu-left"
                        @click="router.get(route('dashboard'))"
                    />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>

<style scoped>
.scrollable-content-tab {
    padding: 0px;
    height: 100%;
}

.splitter-content {
    height: 100%;
}
</style>