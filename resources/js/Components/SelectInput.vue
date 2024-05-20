<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: [String, Number], // This should be the current value of the select
    options: Array,  // Expecting an array of objects { text: String, value: String/Number }
    placeholder: String,
    required: Boolean
});

const emit = defineEmits(['update:modelValue']); // Define the event to emit
const selectInput = ref(null);

function updateValue(event) {
    emit('update:modelValue', event.target.value); // Emit the value when it changes
}

// Optional: Focus method if you want to programmatically focus the select
defineExpose({ focus: () => selectInput.value.focus() });
</script>

<template>
    <select ref="selectInput"
            :value="modelValue"
            @change="updateValue"
            :required="required"
            class="border-gray-300 focus:ring-primary focus:border-primary rounded-xl shadow-sm">
        <option disabled value="" v-if="placeholder">{{ placeholder }}</option>
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.text }}
        </option>
    </select>
</template>
