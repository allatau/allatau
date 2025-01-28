<template>
    META: {{ props.initialMeta }}
    <a-form ref="formRef" name="meta_editor_form" :model="dynamicValidateForm" v-bind="formItemLayoutWithOutLabel">
        <div v-for="(field, index) in dynamicValidateForm.fields" :key="field.key">
            <a-form-item v-bind="index === 0 ? formItemLayout : {}" :label="index === 0 ? 'Метаданные' : ''">
                <div class="field-label">Путь к файлу</div>
                <a-input v-model:value="field.filepath" placeholder="Путь к файлу"
                    style="width: 100%; margin-bottom: 8px" />
                <div style="display: flex; gap: 8px; margin-bottom: 8px">
                    <div style="width: 50%">
                        <div class="field-label">Позиция</div>
                        <a-input-number v-model:value="field.pos" placeholder="Позиция" style="width: 100%" />
                    </div>
                    <div style="width: 50%">
                        <div class="field-label">Длина</div>
                        <a-input-number v-model:value="field.length" placeholder="Длина" style="width: 100%" />
                    </div>
                </div>
                <div class="field-label">Название</div>
                <a-input v-model:value="field.name" placeholder="Название" style="width: 100%; margin-bottom: 8px" />
                <div class="field-label">Описание</div>
                <a-textarea v-model:value="field.description" placeholder="Описание" style="width: 100%" />
                <MinusCircleOutlined v-if="dynamicValidateForm.fields.length > 1" class="dynamic-delete-button"
                    @click="removeField(field)" />
            </a-form-item>
        </div>
        <a-form-item v-bind="formItemLayoutWithOutLabel">
            <a-button type="dashed" style="width: 60%" @click="addField">
                <PlusOutlined />
                Добавить запись
            </a-button>
        </a-form-item>
        <a-form-item v-bind="formItemLayoutWithOutLabel">
            <a-button type="primary" html-type="submit" @click="submitForm">Сохранить</a-button>
            <a-button style="margin-left: 10px" @click="resetForm">Сбросить</a-button>
        </a-form-item>
    </a-form>
</template>

<script lang="ts" setup>
import { reactive, ref, onMounted, watch } from 'vue';
import { MinusCircleOutlined, PlusOutlined } from '@ant-design/icons-vue';
import type { FormInstance } from 'ant-design-vue';
import { message } from 'ant-design-vue';

interface Field {
    filepath: string;
    pos: number;
    length: number;
    name: string;
    description: string;
    key: number;
}

const props = defineProps({
    initialMeta: {
        type: Array as () => Field[],
        default: () => []
    },
    id: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['save']);

const formRef = ref<FormInstance>();
const formItemLayout = {
    labelCol: {
        xs: { span: 24 },
        sm: { span: 4 },
    },
    wrapperCol: {
        xs: { span: 24 },
        sm: { span: 20 },
    },
};
const formItemLayoutWithOutLabel = {
    wrapperCol: {
        xs: { span: 24, offset: 0 },
        sm: { span: 20, offset: 4 },
    },
};

const dynamicValidateForm = reactive<{ fields: Field[] }>({
    fields: [],
});

const submitForm = () => {
    formRef.value
        ?.validate()
        .then(() => {
            const result = dynamicValidateForm.fields.map(field => ({
                filepath: field.filepath,
                pos: field.pos,
                length: field.length,
                name: field.name,
                description: field.description
            }));
            emit('save', { metadata: result });
            message.success('Метаданные успешно сохранены');
        })
        .catch(error => {
            console.log('error', error);
            message.error('Ошибка при сохранении метаданных');
        });
};

const resetForm = () => {
    formRef.value?.resetFields();
};

const removeField = (item: Field) => {
    const index = dynamicValidateForm.fields.indexOf(item);
    if (index !== -1) {
        dynamicValidateForm.fields.splice(index, 1);
    }
};

const addField = () => {
    dynamicValidateForm.fields.push({
        filepath: '',
        pos: 0,
        length: 0,
        name: '',
        description: '',
        key: Date.now(),
    });
};

const updateFormFields = (meta: Field[]) => {
    dynamicValidateForm.fields = meta.map((meta) => ({
        filepath: meta.filepath || '',
        pos: meta.pos || 0,
        length: meta.length || 0,
        name: meta.name || '',
        description: meta.description || '',
        key: Date.now() + Math.random(),
    }));
};

watch(() => props.initialMeta, (newMeta) => {
    if (newMeta && newMeta.length > 0) {
        updateFormFields(newMeta);
    } else {
        dynamicValidateForm.fields = [];
        addField();
    }
}, { immediate: true, deep: true });

onMounted(() => {
    if (props.initialMeta && props.initialMeta.length > 0) {
        updateFormFields(props.initialMeta);
    } else {
        addField();
    }
});
</script>

<style scoped>
.dynamic-delete-button {
    cursor: pointer;
    position: relative;
    top: 4px;
    font-size: 24px;
    color: #999;
    transition: all 0.3s;
}

.dynamic-delete-button:hover {
    color: #777;
}

.dynamic-delete-button[disabled] {
    cursor: not-allowed;
    opacity: 0.5;
}

.field-label {
    font-weight: 500;
    margin-bottom: 4px;
}
</style>