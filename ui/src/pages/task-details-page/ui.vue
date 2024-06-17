<template>
    <div class="tasks-wrapper" v-if="task !== null">
        <a-page-header :loading="loading" v-if="task !== null" style="border: 1px solid rgb(235, 237, 240);width: 100%"
            :title="`${task.name}`" :sub-title="`${uuid}`" @back="() => this.$router.go(-1)">
            <template #tags>
                <a-tag>{{ task.status }}</a-tag>
                <nuxt-link :to="`/project-details?id=${task.project.id}`"><a-tag color="blue">{{ task.project.title
                        }}</a-tag></nuxt-link>

            </template>
            <template #extra>
                <a-button @click="() => check()">Update the task's jobs</a-button>
            </template>
        </a-page-header><br />
        <TasksList :onlyTaskShow="uuid" :fetchInterval="4000" />
        <a-timeline :pending="pendingInfo">
            <a-timeline-item v-for="log in jobsDetails" v-bind:key="log.logInstant">
                <p><a-tag>{{ log.level }}</a-tag> <a-tag>{{ log.logInstant }}</a-tag> <span
                        v-html="log.logMessage"></span>
                </p>
            </a-timeline-item>
            <!-- <a-timeline-item color="red">
                <template #dot><clock-circle-outlined style="font-size: 16px" /></template>
                Technical testing 2015-09-01
            </a-timeline-item> -->
        </a-timeline>
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";

import { TaskModel } from "~/src/entities/task";
import { TaskAdding } from "~/src/widgets/task-adding";
import { TasksList } from "~/src/widgets/tasks-list";

import { ClockCircleOutlined } from '@ant-design/icons-vue';
import { TaskStatus, TaskStatusDescription } from "~/src/shared/types/index.ts"

export default defineComponent({
    components: {
        TasksList,
        TaskAdding,
        ClockCircleOutlined
    },
    setup() {

        const route = useRoute();

        const { fetchById, fetchTaskJobsByTaskId, checkTask } =
            TaskModel.useComposable();
        const { result: resultJobs, loadingJobs } = fetchTaskJobsByTaskId(route.query.id)
        const { result, loading } = fetchById(route.query.id)


        console.log("resultJobs", resultJobs);

        const check = async () => {
            checkTask({ id: route.query.id })
        }



        const task = computed(() => {
            let item = null;
            try {
                item = result.value.taskById
            } catch (error) {

            }

            return item
        })

        const pendingInfo = computed(() => {
            const status = TaskStatus[task.value.status];
            if (status === TaskStatus.RUNNING) {
                return TaskStatusDescription[status]
            } else if (status === TaskStatus.CREATING) {
                return TaskStatusDescription[status]
            } else if (status === TaskStatus.CREATING) {
                return TaskStatusDescription[status]
            } else if (status === TaskStatus.ABORTING) {
                return TaskStatusDescription[status]
            } else if (status === TaskStatus.UNKNOWN) {
                return TaskStatusDescription[status]
            } else {
                return ''
            }

        })

        const jobsDetails = computed(() => {
            let list = [];
            try {
                list = resultJobs.value.TaskJobsByTaskId.map(x => x.json_data).map(x => JSON.parse(x)).map(x => x.metadata).map(x => x["jobRunrDashboardLog-2"]).map(x => x["logLines"]).flat()
                list = list.map((x) => {
                    return {
                        ...x,
                        logMessage: x.logMessage.replaceAll("\n", "</br>")
                    }
                })

                console.log("jobsDetails list", list);


            } catch (error) {

            }

            return list
        })


        return {
            TaskStatus, TaskStatusDescription,
            uuid: route.query.id,
            task,
            loading,
            jobsDetails,
            pendingInfo,
            check
        }
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