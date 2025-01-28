<template>
    <div>
        <a-button type="primary" @click="showModal">Добавить микросервис</a-button>
        <a-modal v-model:visible="visible" title="Добавление микросервиса" width="100%" wrap-class-name="full-modal"
            @ok="handleOk">
            <div style="
                                                    display: flex;
                                                    justify-content: center;
                                                    flex-direction: column;
                                                    max-width: 80%;
                                                    width: 100%;
                                                  ">
                <Form @submit="onSubmit" ref="formRef" />
            </div>
        </a-modal>
    </div>
</template>
<script>
import { defineComponent, ref } from "vue";

import { MicroserviceModel } from "~/src/entities/microservice";

import Form from "./Form.vue";

export default defineComponent({
    components: {
        Form,
        // IframeManagement,
    },
    props: {

    },
    setup(props) {
        const { createItem } = MicroserviceModel.useComposable();

        const formRef = ref(null);
        const visible = ref(false);

        const showModal = () => {
            visible.value = true;
        };

        const closeModal = () => {
            visible.value = false;
        };

        const handleOk = (e) => {
            console.log(e);
            formRef.value.submitForm();
        };

        const onSubmit = async (values) => {
            console.log("values", values);
            createItem({
                name: values.name,
                resource: values.resource,
            });
            closeModal();
        };

        return {
            formRef,
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