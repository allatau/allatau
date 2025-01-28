<template>
    <a-table class="list-table" :loading="isLoading" :columns="columns" :data-source="convertedData" bordered>
        <template #bodyCell="{ column, text, record }">

            <!-- <template v-if="column.key === 'name'">
                <nuxt-link :to="`project-details?id=${record.id}`">{{ record.name }}</nuxt-link>
            </template> -->

            <template v-if="column.key === 'resource'">
                <a :href="record.resource">{{ record.resource }}</a> <a-tag style="margin-left: 8px;"
                    color="green">ONLINE</a-tag>
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

import { MicroserviceModel } from "~/src/entities/microservice";

const columns = [
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "Resource",
        dataIndex: "resource",
        key: "resource",
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
        const { fetch, deleteItem } =
            MicroserviceModel.useComposable();

        const { result, loading } = fetch(props.fetchInterval)

        const convertedData = computed(() => {
            let list = [];

            try {
                list = result.value.microservices
                    .map((payloads) => {
                        return {
                            ...payloads,
                            name: payloads.name,
                            resource: payloads.resource
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
        };
    },
});
</script>

<style>
.list-table {
    width: 100%;
}
</style>