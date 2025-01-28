<template>
    <a-form ref="formRef" name="meta_editor_form" :model="dynamicValidateForm" v-bind="formItemLayoutWithOutLabel">
        <a-form-item v-for="(field, index) in dynamicValidateForm.fields" :key="field.key"
            v-bind="index === 0 ? formItemLayout : {}" :label="index === 0 ? 'Метаданные' : ''"
            :name="['fields', index, 'value']" :rules="{
                required: true,
                message: 'Поле не может быть пустым',
                trigger: 'change',
            }">
            <a-input v-model:value="field.value" placeholder="Введите значение" style="width: 60%; margin-right: 8px" />
            <MinusCircleOutlined v-if="dynamicValidateForm.fields.length > 1" class="dynamic-delete-button"
                @click="removeField(field)" />
        </a-form-item>
        <a-form-item v-bind="formItemLayoutWithOutLabel">
            <a-button type="dashed" style="width: 60%" @click="addField">
                <PlusOutlined />
                Добавить поле
            </a-button>
        </a-form-item>
        <a-form-item v-bind="formItemLayoutWithOutLabel">
            <a-button type="primary" html-type="submit" @click="submitForm">Сохранить</a-button>
            <a-button style="margin-left: 10px" @click="resetForm">Сбросить</a-button>
        </a-form-item>
    </a-form>
</template>

<script lang="ts" setup>
import { reactive, ref, onMounted } from 'vue';
import { MinusCircleOutlined, PlusOutlined } from '@ant-design/icons-vue';
import type { FormInstance } from 'ant-design-vue';

interface Field {
    value: string;
    key: number;
}

const props = defineProps({
    initialMeta: {
        type: Object,
        default: () => ({})
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
            const result = {};
            dynamicValidateForm.fields.forEach((field, index) => {
                result[`field${index + 1}`] = field.value;
            });
            emit('save', result);
        })
        .catch(error => {
            console.log('error', error);
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
        value: '',
        key: Date.now(),
    });
};

onMounted(() => {
    if (Object.keys(props.initialMeta).length) {
        Object.entries(props.initialMeta).forEach(([_, value]) => {
            dynamicValidateForm.fields.push({
                value: String(value),
                key: Date.now(),
            });
        });
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
</style>