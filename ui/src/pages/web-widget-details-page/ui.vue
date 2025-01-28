<template>
    <div class="page-wrapper" v-if="item !== null">
        <a-page-header :loading="loading" :title="`${item.name}`" @back="() => this.$router.go(-1)">
        </a-page-header><br />
        <WebWidgetsList />
        <h2>Iframe текущего виджета:</h2>
        <div class="iframe-wrapper">
            <iframe :src="item.resource" frameborder="0"></iframe>
        </div>

    </div>
</template>
<script>
import { defineComponent, computed } from "vue";

import { WebWidgetModel } from "~/src/entities/web-widget";


import { WebWidgetsList } from "~/src/widgets/web-widgets-list";

export default defineComponent({
    components: {
        WebWidgetsList,
    },
    setup() {

        const route = useRoute();
        const router = useRouter();

        const { fetchById } =
            WebWidgetModel.useComposable();
        const { result, loading } = fetchById(route.query.id)


        const item = computed(() => {
            let item = null;
            try {
                item = result.value.webWidget
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
.page-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.iframe-wrapper {
    display: flex;
    width: 100%;
    min-height: 700px;
    border: 1px solid #fff;
}

.iframe-wrapper iframe {
    width: 100%;
    height: auto;
}
</style>