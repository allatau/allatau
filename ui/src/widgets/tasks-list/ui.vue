<template>
    <a-table class="tasks-table" :loading="isLoading" :columns="columns" :data-source="convertedData" bordered>
        <template #bodyCell="{ column, text, record }">
            <template v-if="column.key === 'name'">
                <nuxt-link :to="`task-details?id=${record.id}`">{{ record.name }}</nuxt-link>
            </template>
            <template v-if="column.key === 'action'">
                <div style="display: flex; gap: 8px">
                    <a-button v-if="TaskStatus[text.status] === TaskStatus.DRAFT" @click="() => startItem(text.id)"
                        type="primary" shape="circle">
                        <template #icon>
                            <CaretRightOutlined />
                        </template>
                    </a-button>
                    <a-button
                        v-if="TaskStatus[text.status] === TaskStatus.RUNNING || TaskStatus[text.status] === TaskStatus.CREATING"
                        shape="circle" @click="() => checkItem(text.id)">
                        <template #icon>
                            <InfoCircleOutlined />
                        </template>
                    </a-button>
                    <a-button v-if="TaskStatus[text.status] === TaskStatus.RUNNING" shape="circle"
                        @click="() => abortItem(text.id)">
                        <template #icon>
                            <StopOutlined />
                        </template>
                    </a-button>


                    <a-button v-if="TaskStatus[text.status] !== TaskStatus.RUNNING" @click="() => deleteItem(text.id)"
                        type="primary" shape="circle" danger>
                        <template #icon>
                            <CloseOutlined />
                        </template>
                    </a-button>
                </div>
            </template>
            <template v-if="column.key === 'script'">
                <a-textarea rows="5" :value="text.script" disabled></a-textarea>
            </template>
            <template v-if="column.key === 'files'">
                <FilesTree :files-raw="text" />
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

import { TaskModel } from "~/src/entities/task";

const columns = [
    // {
    //     title: "ID",
    //     dataIndex: "id",
    //     key: "id",
    // },
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "Computing resource",
        dataIndex: "computing_resource_name",
        key: "computing_resource_name",
    },
    {
        title: "Status",
        dataIndex: "status",
        key: "status",
    },
    // {
    //   title: "Files",
    //   dataIndex: "files",
    //   key: "files",
    // },
    {
        title: "Script",
        key: "script",
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
        const { fetch, deleteTask, startTask, checkTask, abortTask } =
            TaskModel.useComposable();

        const { result, loading } = fetch(props.fetchInterval)

        const convertedData = computed(() => {
            let list = [];

            try {
                list = result.value.tasks
                    .map((payloads) => {
                        return {
                            ...payloads,
                            computing_resource_name: payloads.computing_resource.name,
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

        const deleteItem = async (id) => {
            deleteTask({ id });
        };

        const startItem = async (id) => {
            startTask({ id });
        };

        const checkItem = async (id) => {
            checkTask({ id });
        };

        const abortItem = async (id) => {
            abortTask({ id });
        };

        return {
            convertedData,
            columns,
            isLoading: loading,
            deleteItem,
            startItem,
            checkItem,
            abortItem,
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

.tasks-table {
    width: 100%;
}
</style>
  