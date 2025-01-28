<template>
    <div class="page-wrapper">
        <a-spin :spinning="loading">
            <a-descriptions v-if="data" title="Информация о расчетном случае" bordered>
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
            {{ route.params.id }}
            <a-spin :spinning="loading">Test</a-spin>
            <meta-editor :id="route.params.id" :initial-meta="metaData" @save="handleMetaSave" />
        </a-spin>
    </div>
</template>

<script>
import { defineComponent, computed, ref, watch } from "vue";
import { useRoute } from 'vue-router';
import { CalculationCaseModel } from "~/src/entities/calculation-case";
import MetaEditor from '~/src/widgets/meta-editor/ui.vue';

export default defineComponent({
    components: {
        MetaEditor
    },
    setup() {
        const config = useRuntimeConfig();
        const route = useRoute();
        const metaData = ref([]);

        const { fetchOne, update } = CalculationCaseModel.useComposable();
        const { result: data, loading } = fetchOne(route.params.id);

        watch(() => data.value?.meta, (newMeta) => {
            if (newMeta) {
                try {
                    metaData.value = JSON.parse(newMeta);
                } catch (e) {
                    console.error('Ошибка при разборе мета-данных:', e);
                    metaData.value = [];
                }
            }
        }, { immediate: true });

        const handleMetaSave = async ({ metadata }) => {
            try {
                if (!route.params.id) {
                    throw new Error('ID не предоставлен');
                }
                const metaString = JSON.stringify(metadata);
                await update(route.params.id.toString(), { meta: metaString });
            } catch (e) {
                console.error('Ошибка при сохранении мета-данных:', e);
            }
        };

        return {
            data,
            loading,
            config,
            metaData,
            handleMetaSave,
            route
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