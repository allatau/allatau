<template>
    <div class="projects-wrapper">
        <TaskAdding /> <br />
        <ProjectsList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { TaskAdding } from "~/src/widgets/task-adding";
import { ProjectsList } from "~/src/widgets/projects-list";

export default defineComponent({
    components: {
        ProjectsList,
        TaskAdding,
    },
    setup() { },
});
</script>

<style>
.projects-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.projects-table {
    width: 100%;
}
</style>