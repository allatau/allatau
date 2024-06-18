<template>
  <a-form ref="formRef" :model="formState" name="basic" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }"
    autocomplete="off" @finish="onFinish" @finishFailed="onFinishFailed" style="width: 100%;">
    <a-form-item label="Название" name="name" :rules="[{ required: true, message: 'Введите наименование проекта!' }]">
      <a-input v-model:value="formState.name" />
    </a-form-item>
    <a-form-item label="Ссылка на ресурс" name="resource"
      :rules="[{ required: true, message: 'Введите ссылку на ресурс!' }]">
      <a-input v-model:value="formState.resource" />
    </a-form-item>
  </a-form>
</template>
<script>
import { defineComponent, reactive, ref, toRefs, toRaw, computed, onMounted } from "vue";

import { message } from "ant-design-vue";

import axios from "axios";
export default defineComponent({
  components: {},
  props: {

  },
  emits: ["submit"],
  setup(props, { emit }) {

    const formRef = ref(null);

    const formState = reactive({
      name: "",
      resource: "",
    });


    const onFinish = (values) => {
      let path = ""

      console.log("values", values)

      const data = {
        ...values,
      };
      console.log("Success:", data);
      emit("submit", data);
    };

    const submitForm = () => {
      // formRef.value.validate();
      formRef.value
        .validateFields()
        .then((values) => {
          console.log("Received values of form: ", values);
          console.log("formState: ", toRaw(formState));
          onFinish(values);
          // visible.value = false;
          // formRef.value.resetFields();
          // console.log("reset formState: ", toRaw(formState));
        })
        .catch((info) => {
          console.log("Validate Failed:", info);
        });
    };

    const resetFields = () => {
      formRef.value.resetFields();
    };

    const onFinishFailed = (errorInfo) => {
      console.log("Failed:", errorInfo);
    };


    onMounted(() => {


    });

    return {
      formState,
      onFinish,
      onFinishFailed,
      formRef,
      submitForm,
      resetFields,
    };
  },
});
</script>
