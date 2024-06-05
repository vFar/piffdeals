<script setup>
import { ref, defineEmits } from 'vue';

const props = defineProps({
    modelValue: [String, Number], // current value
    options: Array, // array of { text: String, value: String/Number }
    placeholder: String,
    required: Boolean,
    includeNav: Boolean
});

const emit = defineEmits(['update:modelValue', 'change']);

const selectInput = ref(null);

function updateValue(event) {
    const value = event.target.value === '' && props.includeNav ? null : event.target.value;
    emit('update:modelValue', value);
    emit('change', value); // Emit change event with the new value
}
</script>

<template>
    <select ref="selectInput"
            :value="modelValue"
            @change="updateValue"
            :required="required"
            class="border-gray-300 focus:ring-primary focus:border-primary rounded-xl shadow-sm">
        <option selected disabled value="" v-if="placeholder">{{ placeholder }}</option>
        <option v-for="option in options" :key="option.value" :value="option.value" class="capitalize">
            {{ option.text }}
        </option>
        <option v-if="includeNav" value="">{{ 'NAV' }}</option>
    </select>
</template>
