<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    modelValue: Object,
    type: { type: String, default: 'text' },
    placeholder: String,
    autofocus: Boolean
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 focus:ring-primary focus:border-primary rounded-xl shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
