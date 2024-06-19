<template>
    <a-table class="list-table" :loading="isLoading" :columns="columns" :data-source="convertedData" bordered>
        <template #bodyCell="{ column, text, record }">

            <template v-if="column.key === 'file_id'">
                <a :href="`${config.public.API_URL}/public/files/${record.file_id}`"><a-button>Download</a-button></a>
            </template>

            <template v-if="column.key === 'action'">
                <div style="display: flex; gap: 8px">
                    <a-button @click="() => deleteItemFunc(text.id)" type="primary" shape="circle" danger>
                        <template #icon>
                            <CloseOutlined />
                        </template>
                    </a-button>
                </div>
            </template>
        </template>
    </a-table>
</template>
<script>
import { defineComponent, computed } from "vue";

import {
    CaretRightOutlined,
    CloseOutlined,
    ReloadOutlined,
    StopOutlined,
    InfoCircleOutlined
} from "@ant-design/icons-vue";

import { CalculationCaseModel } from "~/src/entities/calculation-case";

const columns = [
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "File",
        dataIndex: "file_id",
        key: "file_id",
    },
    {
        title: "Action",
        key: "action",
    },
];

export default defineComponent({
    components: {
        CaretRightOutlined,
        CloseOutlined,
        ReloadOutlined,
        StopOutlined,
        InfoCircleOutlined
    },
    props: {
        fetchInterval: {
            type: Number,
            required: false,
            default: 2000
        }
    },
    setup(props) {
        const config = useRuntimeConfig();

        const { fetch, deleteItem } =
            CalculationCaseModel.useComposable();

        const { result, loading } = fetch(props.fetchInterval)

        const convertedData = computed(() => {
            let list = [];

            try {
                list = result.value.calculationCases
                    .map((payloads) => {
                        return {
                            ...payloads,
                            file_id: payloads.file.id
                        };
                    })
                    .reverse();
            } catch (error) {

            }

            return list
        });

        const deleteItemFunc = async (id) => {
            deleteItem({ id });
        };

        return {
            convertedData,
            columns,
            isLoading: loading,
            deleteItemFunc,
            config,
        };
    },
});
</script>

<style>
.list-table {
    width: 100%;
}
</style>