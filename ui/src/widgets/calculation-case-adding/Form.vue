<template>
  <a-form ref="formRef" :model="formState" name="basic" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }"
    autocomplete="off" @finish="onFinish" @finishFailed="onFinishFailed" style="width: 100%;">
    <a-form-item label="Название" name="name" :rules="[{ required: true, message: 'Введите наименование!' }]">
      <a-input v-model:value="formState.name" />
    </a-form-item>
    <a-form-item label="Файл" name="file_id" :rules="[{ required: true, message: 'Введите файл!' }]">
      <!-- <a-input v-model:value="formState.file_id" /> -->

      <a-upload v-model:file-list="formState.fileList" name="file"
        :action="`${config.public.API_URL}/uploading-file-api`" @change="handleChange" @drop="handleDrop"
        :max-count="1">
        <a-button>
          <upload-outlined></upload-outlined>
          Загрузить архив
        </a-button>
      </a-upload>

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
    const config = useRuntimeConfig();

    const formRef = ref(null);

    const formState = reactive({
      name: "",
      file_id: "",
      fileList: []
    });


    const onFinish = (values) => {
      let path = ""

      console.log("values", values)

      const data = {
        ...values,
        filePath: `http://127.0.0.1:8000/public/files/${formState.file_id}`
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

    const handleChange = (info) => {
      if (info.file.status !== "uploading") {
        // console.log("info.file", info.file.xhr.response);
      }
      if (info.file.status === "done") {
        message.success(`${info.file.name} file uploaded successfully`);
        console.log("info.file", info.file);
        const xhr = info.file.xhr;
        console.log("xhr response", JSON.parse(xhr.response));
        const xhrResponse = JSON.parse(xhr.response);
        console.log("xhrResponse.path", xhrResponse.path);
        formState.file_id = xhrResponse.id;
      } else if (info.file.status === "error") {
        message.error(`${info.file.name} file upload failed.`);
      }
    };

    const handleDrop = (file) => {
      console.log("file", file);
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
      handleChange,
      handleDrop,
      config,
    };
  },
});
</script>
