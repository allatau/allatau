<template>
    <div>
        <a-button type="primary" @click="showModal">Добавить проект</a-button>
        <a-modal v-model:visible="visible" title="Добавление проекта" width="100%" wrap-class-name="full-modal"
            @ok="handleOk">
            <div style="
                                                    display: flex;
                                                    justify-content: center;
                                                    flex-direction: column;
                                                    max-width: 80%;
                                                    width: 100%;
                                                  ">
                <AddProjectForm @submit="onSubmit" ref="addProjectFormRef" />
            </div>
        </a-modal>
    </div>
</template>
<script>
import { defineComponent, ref } from "vue";

import { ProjectModel } from "~/src/entities/project";

import AddProjectForm from "./AddProjectForm.vue";



export default defineComponent({
    components: {
        AddProjectForm,
        // IframeManagement,
    },
    props: {

    },
    setup(props) {
        const { create } = ProjectModel.useComposable();

        const addProjectFormRef = ref(null);
        const visible = ref(false);

        const showModal = () => {
            visible.value = true;
        };

        const closeModal = () => {
            visible.value = false;
        };

        const handleOk = (e) => {
            console.log(e);
            addProjectFormRef.value.submitForm();
        };

        const onSubmit = async (values) => {
            console.log("values", values);
            create({
                title: values.title,
                description: values.description,
            });
            closeModal();
        };

        return {
            addProjectFormRef,
            visible,
            showModal,
            handleOk,
            onSubmit,
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