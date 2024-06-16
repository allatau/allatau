<template>
    <div class="tasks-wrapper">

        <a-page-header :loading="loading" v-if="item !== null" :title="`${item.title}`"
            :sub-title="`${item.description}`" @back="() => this.$router.go(-1)">

        </a-page-header><br />
        <TaskAdding /> <br />
        <TasksList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";

import { ProjectModel } from "~/src/entities/project";


import { TaskAdding } from "~/src/widgets/task-adding";
import { TasksList } from "~/src/widgets/tasks-list";

export default defineComponent({
    components: {
        TasksList,
        TaskAdding,
    },
    setup() {

        const route = useRoute();
        const router = useRouter();

        const { fetchById } =
            ProjectModel.useComposable();
        const { result, loading } = fetchById(Number(route.query.id))


        const item = computed(() => {
            let item = null;
            try {
                item = result.value.project
            } catch (error) {

            }

            return item
        })


        return {
            item,
            loading,
            result,
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