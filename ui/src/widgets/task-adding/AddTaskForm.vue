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

    <a-form-item label="Проект" name="projectId">
      <a-input v-model:value="formState.projectId" :disabled="true" />
    </a-form-item>

    <a-form-item label="Скрипт" name="script" :rules="[{ required: true, message: 'Введите наименование задачи!' }]">
      <a-textarea :rows="10" v-model:value="formState.script" />
    </a-form-item>

    <a-form-item label="Есть расчетный кейс?">
      <a-switch v-model:checked="isReadyCase" />
    </a-form-item>

    <a-form-item v-if="isReadyCase === true" label="Расчетный кейс" name="file"
      :rules="[{ required: false, message: 'Загрузите расчетный кейс' }]">
      <!-- <a-upload v-model:file-list="formState.file" name="file" action="http://127.0.0.1:8000/uploading-file-api"
        @change="handleChange" @drop="handleDrop" :max-count="1">
        <a-button>
          <upload-outlined></upload-outlined>
          Загрузить архив
        </a-button>
      </a-upload> -->
      <a-select v-model:value="formState.file_id" placeholder="Выберите расчетный кейс">
        <a-select-option v-for="calculationCase in calculationcases" :value="calculationCase.file.id"
          v-bind:key="calculationCase.id">{{
            calculationCase.name }}</a-select-option>
      </a-select>
    </a-form-item>

    <a-form-item v-if="isReadyCase === false" label="Сервис для формирования расчетного кейса" name="converterService"
      :rules="[{ required: false, message: 'Введите сервис для формирования расчетного кейса!' }]">
      <!-- <a-input v-model:value="formState.converterService" /> -->
      <a-select v-model:value="formState.converterService" placeholder="Выберите вычислительный ресурс">
        <a-select-option v-for="item in microservices" :value="item.resource" v-bind:key="item.id">{{
          item.name }}</a-select-option>
      </a-select>
    </a-form-item>

    <a-form-item :rules="[{ required: false, message: 'Не получены данные из автономного виджета' }]"
      name="numericalModel" v-if="isReadyCase === false" label="Автономный виджет">

      <IframeManagement @changed="handleIframeChange" />

      <a-textarea v-model:value="formState.numericalModel" disabled placeholder="Численная модель" :rows="4" />

    </a-form-item>

    <a-form-item v-if="isReadyCase === true && formState.meta" label="Параметры расчетного кейса">
      <dynamic-form :meta-fields="parsedMeta" v-model="formState.metaValues" />
    </a-form-item>
    {{ formState }}
    <!-- <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
      <a-button type="primary" html-type="submit">Submit</a-button>
    </a-form-item> -->
  </a-form>
</template>
<script>
import { defineComponent, reactive, ref, toRefs, toRaw, computed, onMounted } from "vue";

import { message } from "ant-design-vue";

import { ComputingResourceModel } from "~/src/entities/computing-resource";
import { MicroserviceModel } from "~/src/entities/microservice";
import { CalculationCaseModel } from "~/src/entities/calculation-case";

import IframeManagement from "./IframeManagement.vue";
import DynamicForm from '~/src/widgets/dynamic-form/ui.vue';

import axios from "axios";
export default defineComponent({
  components: {
    IframeManagement,
    DynamicForm
  },
  props: {
    projectId: {
      type: Number,
      required: false,
      default: -1
    },
  },
  emits: ["submit"],
  setup(props, { emit }) {
    const config = useRuntimeConfig();

    const { computingResources } = ComputingResourceModel.useComposable()
    const { items: microservices } = MicroserviceModel.useComposable()
    const { items: calculationcases } = CalculationCaseModel.useComposable()

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
      file_id: "",
      filePath: "",
      computingClusterId: undefined,
      numericalModel: null,
      converterService: null,
      projectId: props.projectId,
      meta: null,
      script:
        "#!/bin/bash\nsource /opt/openfoam9/etc/bashrc\nblockMesh\nicoFoam",
      metaValues: {},
      calculation_case_id: null,
    });
    // https://standalone-widget-cavity.vercel.app

    const parsedMeta = computed(() => {
      try {
        return formState.meta ? JSON.parse(formState.meta) : [];
      } catch (e) {
        console.error('Ошибка при разборе метаданных:', e);
        return [];
      }
    });

    const onFinish = (values) => {
      let converterService = null;
      try {
        if (values.converterService !== null) {
          converterService = {
            resource: values.converterService,
            type: "microservice"
          }
        }
      } catch (error) {
        console.error(error);
      }

      const data = {
        ...values,
        filePath: formState.filePath,
        converterService,
        metaValues: formState.metaValues
      };
      console.log("Success:", data);
      emit("submit", data);
    };

    watch(isReadyCase, (newValue, oldValue) => {
      if (newValue === true) {
        formState.converterService = null;
      } else {
        formState.file_id = "";
        formState.filePath = "";
        formState.meta = null;
      }
    });

    watch(formState, (newValue, oldValue) => {
      if (formState.file_id !== "") {
        formState.filePath = `${config.public.API_URL}/public/files/${formState.file_id}`;
      } else {
        formState.filePath = "";
        formState.meta = null;
      }
    });

    watch(() => formState.file_id, async (newFileId) => {
      if (newFileId) {
        try {
          const selectedCase = calculationcases.value.find(c => c.file.id === newFileId);

          if (selectedCase) {
            formState.calculation_case_id = selectedCase.id;
            if (selectedCase.meta) {
              formState.meta = selectedCase.meta;
            } else {
              formState.meta = null;
            }
          }
        } catch (error) {
          console.error('Ошибка при получении метаданных:', error);
          formState.meta = null;
          formState.calculation_case_id = null;
        }
      } else {
        formState.meta = null;
        formState.calculation_case_id = null;
      }
    });

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
      formState.projectId = props.projectId

    });

    const handleIframeChange = (data) => {
      console.log("handleIframeChange", data);

      let endpoint = ""

      try {
        endpoint = data[0].url
      } catch (error) {
        console.error(error);
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
      microservices,
      file,
      uploading,
      // handleChange,
      // handleDrop,
      isReadyCase,
      handleIframeChange,
      calculationcases,
      parsedMeta,
    };
  },
});
</script>
