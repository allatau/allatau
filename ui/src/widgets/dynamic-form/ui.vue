<template>
    <a-form :model="formState" layout="vertical">
        <template v-for="field in metaFields" :key="field.name">
            <a-form-item :label="field.name" :name="field.filepath">
                <a-input v-model:value="formState[field.filepath]" :placeholder="field.description || ''" />
            </a-form-item>
        </template>
        <a-form-item>
            <a-button type="primary" @click="handleSubmit">Сохранить</a-button>
        </a-form-item>
    </a-form>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue';

const props = defineProps({
    metaFields: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['submit']);

const formState = ref({});

watch(() => props.metaFields, (newFields) => {
    const newFormState = {};
    newFields.forEach(field => {
        newFormState[field.filepath] = '';
    });
    formState.value = newFormState;
}, { immediate: true });

const handleSubmit = () => {
    emit('submit', formState.value);
};
</script>