<template>
    <div class="tasks-wrapper">
        <TaskAdding /> <br />
        <TasksList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { TaskAdding } from "~/src/widgets/task-adding";
import { TasksList } from "~/src/widgets/tasks-list";

export default defineComponent({
    components: {
        TasksList,
        TaskAdding,
    },
    setup() { },
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
  