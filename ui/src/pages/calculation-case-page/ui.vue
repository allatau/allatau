<template>
    <div class="page-wrapper">
        <a-spin :spinning="loading">
            <a-descriptions v-if="data" title="Информация о расчетном кейсе" bordered>
                <a-descriptions-item label="Название">
                    {{ data.name }}
                </a-descriptions-item>
                <a-descriptions-item label="Файл">
                    <a :href="`${config.public.API_URL}/public/files/${data.file.id}`">
                        <a-button>Скачать</a-button>
                    </a>
                </a-descriptions-item>
            </a-descriptions>
            <br />

            <a-tabs v-model:activeKey="activeTab">
                <a-tab-pane key="meta" tab="Мета-данные">
                    <meta-editor :id="route.params.id" :initial-meta="metaData" @save="handleMetaSave" />
                </a-tab-pane>
                <a-tab-pane key="form" tab="Форма">
                    <dynamic-form :meta-fields="metaData" @submit="handleFormSubmit" />
                </a-tab-pane>
            </a-tabs>
        </a-spin>
    </div>
</template>

<script>
import { defineComponent, computed, ref, watch } from "vue";
import { useRoute } from 'vue-router';
import { message } from 'ant-design-vue';
import { CalculationCaseModel } from "~/src/entities/calculation-case";
import MetaEditor from '~/src/widgets/meta-editor/ui.vue';
import DynamicForm from '~/src/widgets/dynamic-form/ui.vue';

export default defineComponent({
    components: {
        MetaEditor,
        DynamicForm
    },
    setup() {
        const config = useRuntimeConfig();
        const route = useRoute();
        const metaData = ref([]);
        const activeTab = ref('meta');

        const { fetchOne, update } = CalculationCaseModel.useComposable();
        const { result: data, loading } = fetchOne(route.params.id);

        watch(() => data.value?.meta, (newMeta) => {
            if (newMeta) {
                try {
                    const parsedMeta = JSON.parse(newMeta);
                    metaData.value = Array.isArray(parsedMeta) ? parsedMeta : [];
                    console.log('Загруженные метаданные:', metaData.value);
                } catch (e) {
                    console.error('Ошибка при разборе мета-данных:', e);
                    metaData.value = [];
                }
            } else {
                metaData.value = [];
            }
        }, { immediate: true });

        const handleMetaSave = async ({ metadata }) => {
            try {
                if (!route.params.id) {
                    throw new Error('ID не предоставлен');
                }
                const metaString = JSON.stringify(metadata);
                await update(route.params.id.toString(), { meta: metaString });
                message.success('Метаданные успешно обновлены');
            } catch (e) {
                console.error('Ошибка при сохранении мета-данных:', e);
                message.error('Произошла ошибка при сохранении метаданных');
            }
        };

        const handleFormSubmit = (formData) => {
            console.log('Данные формы:', formData);
            message.success('Форма отправлена');
        };

        return {
            data,
            loading,
            config,
            metaData,
            handleMetaSave,
            route,
            activeTab,
            handleFormSubmit
        };
    },
});
</script>

<style>
.page-wrapper {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
</style>