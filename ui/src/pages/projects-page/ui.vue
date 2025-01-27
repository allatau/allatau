<template>
    <div class="projects-wrapper">
        <ProjectAdding /> <br />
        <ProjectsList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { ProjectAdding } from "~/src/widgets/project-adding";
import { ProjectsList } from "~/src/widgets/projects-list";

export default defineComponent({
    components: {
        ProjectsList,
        ProjectAdding,
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