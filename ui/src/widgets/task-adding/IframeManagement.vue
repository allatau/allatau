<template>
    <a-form ref="formRef" name="dynamic_form_nest_item" :model="dynamicValidateForm" @finish="onFinish">
        <a-form-item v-for="(item, index) in dynamicValidateForm.iframes" :key="item.id">
            <a-space style="display: flex; margin-bottom: 8px" align="baseline">
                <a-form-item :name="['iframes', index, 'url']" :rules="{
                    required: true,
                    message: 'Url required',
                }">
                    <a-input v-model:value="item.url" placeholder="Iframe url" />
                </a-form-item>

                <SaveOutlined @click="saveIframe(item)" />
                <MinusCircleOutlined @click="removeIframe(item)" />
            </a-space>

            <div v-if="item.currentIframe?.length > 0" class="iframe-wrapper">
                <iframe v-show="loaderState.isLoaded === true" :id="item.guid" :src="item.currentIframe"
                    @load="loaderState.enableLoaded()" frameborder="0"></iframe>
                <a-skeleton active v-show="loaderState.isLoaded === false" :paragraph="{ rows: 12 }" />
            </div>

        </a-form-item>
        <a-form-item v-if="dynamicValidateForm.iframes.length === 0">
            <a-form-item>
                <a-button type="dashed" block @click="addIframe">
                    <PlusOutlined />
                    Add iframe
                </a-button>
            </a-form-item>
            <a-form-item>
                <a-button type="primary" html-type="submit">Save iframes</a-button>
            </a-form-item>
        </a-form-item>
    </a-form>
</template>

<script>
import { MinusCircleOutlined, PlusOutlined, SaveOutlined } from '@ant-design/icons-vue';
import { defineComponent, onMounted, reactive, ref, toRaw } from 'vue';

import { useLoaderState } from "~/src/shared/lib"

export default defineComponent({
    components: {
        MinusCircleOutlined,
        PlusOutlined,
        SaveOutlined
    },

    setup(props, { emit }) {
        const loaderState = ref(useLoaderState());

        let initIframes = [];
        if (localStorage.getItem('iframes')) {
            initIframes = JSON.parse(localStorage.getItem('iframes'))
        }
        const formRef = ref();
        const dynamicValidateForm = reactive({
            iframes: initIframes
        });

        const removeIframe = item => {
            let index = dynamicValidateForm.iframes.indexOf(item);

            if (index !== -1) {
                dynamicValidateForm.iframes.splice(index, 1);
            }

            emitIframes()
        };

        const saveIframe = (item) => {
            const index = dynamicValidateForm.iframes.indexOf(item);

            dynamicValidateForm.iframes[index].currentIframe = item.url

            emitIframes()
            loaderState.value.enableLoading()
        };

        const emitIframes = () => {

            setTimeout(() => {
                emit("changed", toRaw(dynamicValidateForm.iframes))
            }, 100);
        }

        const addIframe = () => {

            const generateGuidQuickly = () => {
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            }

            dynamicValidateForm.iframes.push({
                guid: generateGuidQuickly(),
                url: '',
                id: Date.now(),
                currentIframe: ''
            });
        };

        const onFinish = values => {
            console.log('Received values of form:', values);
            console.log('dynamicValidateForm.iframes:', dynamicValidateForm.iframes);

            localStorage.setItem('iframes', JSON.stringify(dynamicValidateForm.iframes))
        };

        onMounted(() => {
            emitIframes()

        });

        return {
            formRef,
            dynamicValidateForm,
            onFinish,
            removeIframe,
            addIframe,
            saveIframe,
            loaderState,
        };
    },

});
</script>

<style>
.iframe-wrapper {
    display: flex;
    width: 100%;
    min-height: 700px;
    border: 1px solid #fff;
}

.iframe-wrapper iframe {
    width: 100%;
    height: auto;
}
</style>
