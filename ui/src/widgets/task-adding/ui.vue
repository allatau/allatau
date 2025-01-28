<template>
    <div>
        <a-button type="primary" @click="showModal">Добавить задачу</a-button>
        <a-modal v-model:visible="visible" title="Добавление задачи" width="100%" wrap-class-name="full-modal"
            @ok="handleOk">
            <div style="
                                                    display: flex;
                                                    justify-content: center;
                                                    flex-direction: column;
                                                    max-width: 80%;
                                                    width: 100%;
                                                  ">
                <AddTaskForm @submit="onSubmit" ref="addTaskFormRef" :project-id="projectId" />
                <!-- <IframeManagement /> -->

            </div>
        </a-modal>
    </div>
</template>
<script>
import { defineComponent, ref } from "vue";

import { TaskModel } from "~/src/entities/task";

import AddTaskForm from "./AddTaskForm.vue";
// import IframeManagement from "~/components/Tasks/IframeManagement.vue";



export default defineComponent({
    components: {
        AddTaskForm,
        // IframeManagement,
    },
    props: {
        projectId: {
            type: Number,
            required: false,
            default: -1
        },
    },
    setup(props) {
        const { createTask } = TaskModel.useComposable();
        const config = useRuntimeConfig();

        const addTaskFormRef = ref(null);
        const visible = ref(false);

        const showModal = () => {
            visible.value = true;
        };

        const closeModal = () => {
            visible.value = false;
        };

        const handleOk = (e) => {
            console.log(e);
            addTaskFormRef.value.submitForm();
        };

        const onSubmit = async (values) => {
            console.log("values", values);
            const p = {
                name: values.name,
                computing_resource_id: values.computingClusterId,
                script: values.script,
                computational_model_resource: values.filePath,
                converter_service: JSON.stringify(values.converterService),
                numerical_model: values.numericalModel,
                project_id: values.projectId,
                calculation_case_id: values.file_id || null,
                meta_values: JSON.stringify(values.metaValues || {})
            }
            console.log("p", p);
            createTask(p);
            closeModal();
        };

        return {
            addTaskFormRef,
            visible,
            showModal,
            handleOk,
            onSubmit,
            projectId: props.projectId
        };
    },
});
</script>
<style lang="scss">
.full-modal {
    .ant-modal {
        max-width: 100%;
        height: 100%;
        top: 0;
        padding-bottom: 0;
        margin: 0;
    }

    .ant-modal-content {
        display: flex;
        flex-direction: column;
    }

    .ant-modal-body {
        flex: 1;
    }
}

.ant-modal-content {
    height: auto !important;
    min-height: 100vh;
}
</style>