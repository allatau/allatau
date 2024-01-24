<template>
  <a-form ref="formRef" :model="formState" name="basic" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }"
    autocomplete="off" @finish="onFinish" @finishFailed="onFinishFailed" style="width: 100%">
    <a-form-item label="Название" name="name" :rules="[{ required: true, message: 'Введите значение' }]">
      <a-input v-model:value="formState.name" />
    </a-form-item>

    <a-form-item label="Хост" name="host" :rules="[{ required: true, message: 'Введите значение' }]">
      <a-input v-model:value="formState.host" />
    </a-form-item>

    <a-form-item label="Порт" name="port" :rules="[{ required: true, message: 'Введите значение' }]">
      <a-input v-model:value="formState.port" />
    </a-form-item>

    <a-form-item label="Логин" name="login" :rules="[{ required: true, message: 'Введите значение' }]">
      <a-input v-model:value="formState.login" />
    </a-form-item>

    <a-form-item label="Пароль" name="password" :rules="[{ required: true, message: 'Введите значение' }]">
      <a-input-password v-model:value="formState.password" />
    </a-form-item>

    <!-- <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
      <a-button type="primary" html-type="submit">Submit</a-button>
    </a-form-item> -->
  </a-form>
</template>
<script>
import { defineComponent, reactive, ref, toRaw } from "vue";

export default defineComponent({
  emits: ["submit"],
  setup(props, { emit }) {
    const formRef = ref(null);

    const formState = reactive({
      name: "",
      host: "localhost",
      port: "2022",
      login: "sshuser",
      password: "123",
    });

    const onFinish = (values) => {
      console.log("Success:", values);
      emit("submit", values);
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
