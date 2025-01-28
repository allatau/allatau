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
            <meta-editor :initial-meta="data?.meta" @save="handleMetaSave" />
        </a-spin>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { CalculationCaseModel } from "~/src/entities/calculation-case";
import MetaEditor from '~/src/widgets/meta-editor/ui.vue';

export default defineComponent({
    components: {
        MetaEditor
    },
    setup() {
        const config = useRuntimeConfig();
        const route = useRoute();

        const { fetchOne, update } = CalculationCaseModel.useComposable();
        const { result: data, loading } = fetchOne(route.params.id);

        const handleMetaSave = async (newMeta) => {
            await update(route.params.id, { meta: newMeta });
        };

        return {
            data,
            loading,
            config,
            handleMetaSave
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