<template>
  <a-form ref="formRef" :model="formState" name="basic" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }"
    autocomplete="off" @finish="onFinish" @finishFailed="onFinishFailed" style="width: 100%;">
    <a-form-item label="Название" name="name" :rules="[{ required: true, message: 'Введите наименование задачи!' }]">
      <a-input v-model:value="formState.name" />
    </a-form-item>
    <a-form-item label="Вычислительный ресурс" name="computingClusterId"
      :rules="[{ required: true, message: 'Выберите вычислительный ресурс' }]">
      <a-select v-model:value="formState.computingClusterId" placeholder="Выберите вычислительный ресурс">
        <a-select-option v-for="resource in computingresources" :value="resource.id" v-bind:key="resource.id">{{
          resource.name }}</a-select-option>
      </a-select>
    </a-form-item>

    <a-form-item label="Скрипт" name="script" :rules="[{ required: true, message: 'Введите наименование задачи!' }]">
      <a-textarea :rows="10" v-model:value="formState.script" />
    </a-form-item>

    <a-form-item label="Есть расчетный кейс?">
      <a-switch v-model:checked="isReadyCase" />
    </a-form-item>

    <a-form-item v-if="isReadyCase === true" label="Расчетный кейс" name="file"
      :rules="[{ required: false, message: 'Загрузите расчетный кейс' }]">
      <a-upload v-model:file-list="formState.file" name="file" action="http://127.0.0.1:8000/uploading-file-api"
        @change="handleChange" @drop="handleDrop" :max-count="1">
        <a-button>
          <upload-outlined></upload-outlined>
          Загрузить архив
        </a-button>
      </a-upload>
    </a-form-item>

    <a-form-item v-if="isReadyCase === false" label="Сервис для формирования расчетного кейса" name="converterService"
      :rules="[{ required: false, message: 'Введите сервис для формирования расчетного кейса!' }]">
      <a-input v-model:value="formState.converterService" />
    </a-form-item>

    <a-form-item :rules="[{ required: false, message: 'Не получены данные из автономного виджета' }]"
      name="numericalModel" v-if="isReadyCase === false" label="Автономный виджет">

      <IframeManagement @changed="handleIframeChange" />

      <a-textarea v-model:value="formState.numericalModel" disabled placeholder="Численная модель" :rows="4" />

    </a-form-item>




    <!-- <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
      <a-button type="primary" html-type="submit">Submit</a-button>
    </a-form-item> -->
  </a-form>
</template>
<script>
import { defineComponent, reactive, ref, toRefs, toRaw, computed, onMounted } from "vue";

import { message } from "ant-design-vue";

import { ComputingResourceModel } from "~/src/entities/computing-resource";

import IframeManagement from "./IframeManagement.vue";

import axios from "axios";
export default defineComponent({
  components: {
    IframeManagement
  },
  emits: ["submit"],
  setup(props, { emit }) {
    const { computingResources } = ComputingResourceModel.useComposable()

    const computingresources = computed(() => {
      return computingResources.value.map((payloads) => {
        return {
          ...payloads,
        };
      });
    });

    const isReadyCase = ref(true);

    const formRef = ref(null);

    const file = ref([]);

    const formState = reactive({
      name: "",
      file: [],
      filePath: "",
      computingClusterId: undefined,
      numericalModel: null,
      converterService: "https://microservice-zero.vercel.app",
      script:
        "#!/bin/bash\nsource /opt/openfoam9/etc/bashrc\nblockMesh\nicoFoam",
    });
    // https://standalone-widget-cavity.vercel.app

    const onFinish = (values) => {
      let path = ""

      try {
        path = values?.file[0]?.response?.path
      } catch (error) {

      }

      let converterService = null

      try {
        if (values.converterService !== null) {
          converterService = {
            resource: values.converterService,
            type: "microservice"
          }
        }

      } catch (error) {

      }

      const data = {
        ...values,
        filePath: path,
        converterService
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

    const uploading = ref(false);

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
        formState.filePath = xhrResponse.path;
      } else if (info.file.status === "error") {
        message.error(`${info.file.name} file upload failed.`);
      }
    };

    const handleDrop = (file) => {
      console.log("file", file);
    };

    onMounted(() => {
      window.addEventListener(
        "message",
        (event) => {
          if (event.data.method === "getNumericalModelParameters") {
            console.log("Data from iframe", event.data);
            formState.numericalModel = JSON.stringify(event.data);
          }

        },
        false
      );
    });

    const handleIframeChange = (data) => {
      console.log("handleIframeChange", data);

      let endpoint = ""

      try {
        endpoint = data[0].url
      } catch (error) {

      }

      if (endpoint !== "") {

      } else {
        formState.converterService = null;
      }


    };

    return {
      formState,
      onFinish,
      onFinishFailed,
      formRef,
      submitForm,
      resetFields,
      computingresources,
      file,
      uploading,
      handleChange,
      handleDrop,
      isReadyCase,
      handleIframeChange,
    };
  },
});
</script>
