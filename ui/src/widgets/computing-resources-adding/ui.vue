<template>
    <div>
        <a-button type="primary" @click="showModal">Добавить вычислительный ресурс</a-button>
        <a-modal v-model:visible="visible" title="Добавление вычислительного ресурса" width="100%"
            wrap-class-name="full-modal" @ok="handleOk">
            <div style="
            display: flex;
            justify-content: center;
            max-width: 80%;
            width: 100%;
          ">
                <AddForm @submit="onSubmit" ref="addAddFormRef" />
            </div>
        </a-modal>
    </div>
</template>
<script>
import { defineComponent, ref } from "vue";

import { ComputingResourceModel } from "~/src/entities/computing-resource";

import AddForm from "./AddComputingResourceForm.vue";

export default defineComponent({
    components: {
        AddForm,
    },
    setup() {
        const { createComputingResource } = ComputingResourceModel.useComposable();

        const addAddFormRef = ref(null);
        const visible = ref(false);

        const showModal = () => {
            visible.value = true;
        };

        const closeModal = () => {
            visible.value = false;
        };

        const handleOk = (e) => {
            console.log(e);
            addAddFormRef.value.submitForm();
        };

        const onSubmit = async (values) => {
            console.log("values", values);
            createComputingResource({
                name: values.name,
                host: values.host,
                port: values.port,
                login: values.login,
                password: values.password,
            });
            closeModal();
        };

        return {
            addAddFormRef,
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
        top: 0;
        padding-bottom: 0;
        margin: 0;
    }

    .ant-modal-content {
        display: flex;
        flex-direction: column;
        height: calc(100vh);
    }

    .ant-modal-body {
        flex: 1;
    }
}
</style>
  