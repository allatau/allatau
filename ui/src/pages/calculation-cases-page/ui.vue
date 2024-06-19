<template>
    <div class="page-wrapper">
        <WebwidgetAdding /> <br />
        <CalculationCasesList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { WebwidgetAdding } from "~/src/widgets/web-widget-adding";
import { CalculationCasesList } from "~/src/widgets/calculation-cases-list";

export default defineComponent({
    components: {
        CalculationCasesList,
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