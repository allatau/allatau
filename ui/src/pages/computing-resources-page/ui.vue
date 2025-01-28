<template>
    <div class="computing-resource-wrapper">
        <ComputingResourcesAdding /> <br />
        <a-table is-bordered :loading="isLoading" :columns="columns" :data-source="convertedData"
            class="computing-resource-table">
            <template #bodyCell="{ column, text }">
                <template v-if="column.key === 'action'">
                    <div style="display: flex; gap: 8px">
                        <a-button @click="() => deleteItem(text.id)" type="primary" shape="circle" danger>
                            <template #icon>
                                <CloseOutlined />
                            </template>
                        </a-button>
                    </div>
                </template>
            </template>
        </a-table>
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";
import { SmileOutlined, DownOutlined, CloseOutlined } from "@ant-design/icons-vue";


import { Table } from "ant-design-vue";
import { ComputingResourcesAdding } from "~/src/widgets/computing-resources-adding";

import { ComputingResourceModel } from "~/src/entities/computing-resource";

const columns = [
    {
        title: "ID",
        dataIndex: "id",
        key: "id",
    },
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "Host",
        dataIndex: "host",
        key: "host",
    },
    {
        title: "Port",
        dataIndex: "port",
        key: "port",
    },
    {
        title: "Login",
        dataIndex: "login",
        key: "login",
    },
    {
        title: "Action",
        key: "action",
    },
];

export default defineComponent({
    components: {
        SmileOutlined,
        DownOutlined,
        ATable: Table,
        ComputingResourcesAdding,

        CloseOutlined,
    },
    setup() {
        const { computingResources, isLoading, error, deleteComputingResource } = ComputingResourceModel.useComposable();

        const convertedData = computed(() => {
            return computingResources.value.map((payloads) => {
                return {
                    ...payloads,
                };
            });
        });

        const deleteItem = async (id) => {
            deleteComputingResource({ id });
        };

        return {
            convertedData,
            columns,
            isLoading,
            deleteItem,
        };
    },
});
</script>
  
<style>
.computing-resource-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.computing-resource-table {
    width: 100%;
}
</style>
  