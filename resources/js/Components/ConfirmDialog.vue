<script setup>
import { ref, computed } from 'vue'

const emit = defineEmits(['ok', 'cancel'])
const props = defineProps({
    show: Boolean,
    title: String,
    message: String
});

const groupNumber = ref('')
const partidaNumber = ref('')
const groupError = ref(false)
const partidaError = ref(false)

const isValid = computed(() => {
    const isGroupValid = /^\d+$/.test(groupNumber.value) && parseInt(groupNumber.value) > 0
    const isPartidaValid = /^\d+$/.test(partidaNumber.value) && parseInt(partidaNumber.value) > 0
    groupError.value = !isGroupValid
    partidaError.value = !isPartidaValid
    return isGroupValid && isPartidaValid
})

const filterNumberInput = (event) => {
    event.target.value = event.target.value.replace(/\D/g, '')
    if (event.target.value.startsWith('0')) {
        event.target.value = event.target.value.replace(/^0+/, '')
    }
}

const closeModal = () => {
    emit('cancel')
}

const confirm = () => {
    if (isValid.value) {
        emit('ok', { groupNumber: groupNumber.value, partidaNumber: partidaNumber.value })
    }
}
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-30"
        style="z-index: 9999;"
    >
        <q-card
            class="q-pa-md bg-white shadow-10 rounded-borders"
            style="width: 400px; max-width: 90%; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);"
        >
            <q-card-section>
                <div class="text-h6">{{ title }}</div>
                <p class="text-body1">{{ message }}</p>
            </q-card-section>

            <q-card-section>
                <q-input
                    v-model="groupNumber"
                    class="col"
                    label="Група номер"
                    hint="Номер група за този процес"
                    type="number"
                    autofocus
                    :error="groupError"
                    :error-message="groupError ? 'Моля, въведете номер на група. Само цяло положително число.' : ''"
                    @input="filterNumberInput"
                    min="1"
                />
                <q-input
                    v-model="partidaNumber"
                    class="col q-mt-md"
                    label="Партида номер"
                    hint="Номер партида за този процес"
                    type="number"
                    :error="partidaError"
                    :error-message="partidaError ? 'Моля, въведете номер на партида. Само цяло положително число.' : ''"
                    @input="filterNumberInput"
                    min="1"
                />
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    flat
                    label="Откажи"
                    color="grey"
                    @click="closeModal"
                />
                <q-btn
                    label="Потвърди"
                    color="primary"
                    @click="confirm"
                    :disable="!isValid"
                />
            </q-card-actions>
        </q-card>
    </div>
</template>