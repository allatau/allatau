<template>
    <div class="page-wrapper">
        <WebwidgetAdding /> <br />
        <WebWidgetsList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { WebwidgetAdding } from "~/src/widgets/web-widget-adding";
import { WebWidgetsList } from "~/src/widgets/web-widgets-list";

export default defineComponent({
    components: {
        WebWidgetsList,
        WebwidgetAdding,
    },
    setup() { },
});
</script>

<style>
.page-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
</style>