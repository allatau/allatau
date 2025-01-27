<template>
    <a-table class="projects-table" :loading="isLoading" :columns="columns" :data-source="convertedData" bordered>
        <template #bodyCell="{ column, text, record }">

            <template v-if="column.key === 'name'">
                <nuxt-link :to="`project-details?id=${record.id}`">{{ record.name }}</nuxt-link>
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

import { TaskStatus } from "~/src/shared/types/index.ts"


import {
    CaretRightOutlined,
    CloseOutlined,
    ReloadOutlined,
    StopOutlined,
    InfoCircleOutlined
} from "@ant-design/icons-vue";

import { TaskAdding } from "~/src/widgets/task-adding";
import FilesTree from "./FilesTree.vue";

import { ProjectModel } from "~/src/entities/project";

const columns = [
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "Description",
        dataIndex: "desc",
        key: "desc",
    },
    {
        title: "Action",
        key: "action",
    },
];

export default defineComponent({
    components: {
        CaretRightOutlined,
        TaskAdding,
        CloseOutlined,
        FilesTree,
        ReloadOutlined,
        StopOutlined,
        InfoCircleOutlined
    },
    props: {
        onlyTaskShow: {
            type: String,
            required: false,
            default: null
        },
        fetchInterval: {
            type: Number,
            required: false,
            default: 2000
        }
    },
    setup(props) {
        const { fetch, deleteItem } =
            ProjectModel.useComposable();

        const { result, loading } = fetch(props.fetchInterval)

        const convertedData = computed(() => {
            let list = [];

            try {
                list = result.value.projects
                    .map((payloads) => {
                        return {
                            ...payloads,
                            name: payloads.title,
                            desc: payloads.description
                        };
                    })
                    .reverse();
            } catch (error) {

            }

            if (props.onlyTaskShow !== null) {
                list = list.filter(x => x.id == props.onlyTaskShow)
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
            TaskStatus,
            onlyTaskShow: props.onlyTaskShow

        };
    },
});
</script>

<style>
.tasks-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.projects-table {
    width: 100%;
}
</style>