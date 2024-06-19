<template>
    <div style="display: flex; max-width: 600px;margin: 0 auto; padding-top: 100px; justify-content: center;">
        <a-form :model="formState" name="basic" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }" autocomplete="off"
            @finish="onFinish" @finishFailed="onFinishFailed">
            <a-form-item label="Email" name="email" :rules="[{ required: true, message: 'Please input your email!' }]">
                <a-input v-model:value="formState.email" />
            </a-form-item>

            <a-form-item label="Пароль" name="password"
                :rules="[{ required: true, message: 'Please input your password!' }]">
                <a-input-password v-model:value="formState.password" />
            </a-form-item>

            <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                <a-button type="primary" html-type="submit">Submit</a-button>
            </a-form-item>
        </a-form>
    </div>
</template>
<script lang="ts">
import { defineComponent, reactive } from 'vue';

import { ViewerModel } from "~/src/entities/viewer";



interface FormState {
    email: string;
    password: string;
}

definePageMeta({
    layout: 'auth',
    middleware: ['auth'],
});


export default defineComponent({
    setup() {
        const { login } = ViewerModel.useStore();
        // const auth = useAuth()

        const formState = reactive<FormState>({
            email: '',
            password: ''
        });
        const onFinish = async (values: any) => {
            await login(formState.email, formState.password)
            navigateTo("/");
        };

        const onFinishFailed = (errorInfo: any) => {
            console.log('Failed:', errorInfo);
        };
        return {
            formState,
            onFinish,
            onFinishFailed,
        };
    },
});
</script>