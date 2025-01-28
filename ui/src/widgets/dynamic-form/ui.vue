<template>
    <a-form :model="formState" layout="vertical">
        <template v-for="field in metaFields" :key="field.name">
            <a-form-item :label="field.name" :name="field.filepath">
                <a-input v-model:value="formState[field.filepath]" :placeholder="field.description || ''"
                    @input="handleInput" />
            </a-form-item>
        </template>
    </a-form>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue';

const props = defineProps({
    metaFields: {
        type: Array,
        required: true
    },
    modelValue: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:modelValue']);

const formState = ref(props.modelValue || {});

// Синхронизация при изменении входных данных
watch(() => props.modelValue, (newValue) => {
    formState.value = newValue;
}, { deep: true });

// Синхронизация при изменении metaFields
watch(() => props.metaFields, (newFields) => {
    const newFormState = {};
    newFields.forEach(field => {
        newFormState[field.filepath] = formState.value[field.filepath] || '';
    });
    formState.value = newFormState;
    emit('update:modelValue', formState.value);
}, { immediate: true });

// Обработчик изменения значений в форме
const handleInput = () => {
    emit('update:modelValue', formState.value);
};
</script>