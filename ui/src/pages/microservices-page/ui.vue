<template>
    <div class="list-wrapper">
        <MicroserviceAdding /> <br />
        <MicroservicesList />
    </div>
</template>
<script>
import { defineComponent, computed } from "vue";


import { MicroserviceAdding } from "~/src/widgets/microservice-adding";
import { MicroservicesList } from "~/src/widgets/microservices-list";

export default defineComponent({
    components: {
        MicroservicesList,
        MicroserviceAdding,
    },
    setup() { },
});
</script>

<style>
.list-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
</style>