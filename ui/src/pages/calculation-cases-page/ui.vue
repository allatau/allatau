<template>
    <div class="page-wrapper">
        <CalculationCaseAdding /> <br />
        <CalculationCasesList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { CalculationCaseAdding } from "~/src/widgets/calculation-case-adding";
import { CalculationCasesList } from "~/src/widgets/calculation-cases-list";

export default defineComponent({
    components: {
        CalculationCasesList,
        CalculationCaseAdding,
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