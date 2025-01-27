<template>
  <a-tree
    v-model:expandedKeys="expandedKeys"
    v-model:selectedKeys="selectedKeys"
    show-line
    :tree-data="treeData"
  >
    <template #switcherIcon="{ switcherCls }"
      ><down-outlined :class="switcherCls"
    /></template>
  </a-tree>
</template>
<script>
import { DownOutlined } from "@ant-design/icons-vue";
import { defineComponent, ref, computed, toRef, watch } from "vue";
export default defineComponent({
  components: {
    DownOutlined,
  },
  props: ["filesRaw"],

  setup(props) {
    const expandedKeys = ref([]);
    const selectedKeys = ref([]);

    const parsedFiles = ref("");
    parsedFiles.value = JSON.parse(props.filesRaw);

    const filterFolder = (objectFolder) => {
      if (Object.keys(objectFolder).length > 0) {
        return Object.keys(objectFolder).map((fileItem) => {
          const uuidGenerated = "id" + Math.random().toString(16).slice(2);
          if (objectFolder[fileItem].filename !== undefined) {
            return {
              title: objectFolder[fileItem].filename,
              key: uuidGenerated,
            };
          } else {
            return {
              title: fileItem,
              key: uuidGenerated,
              children: filterFolder(objectFolder[fileItem]),
            };
          }
        });
      } else {
        return [];
      }
    };

    watch(
      () => props.filesRaw,
      (raw, prevRaw) => {
        // alert("test");
        parsedFiles.value = JSON.parse(raw);
      }
    );

    const treeData = computed(() => {
      return [
        {
          title: "Files",
          key: "0-0",
          children: filterFolder(parsedFiles.value),
        },
      ];
    });

    return {
      expandedKeys,
      selectedKeys,
      treeData,
    };
  },
});
</script>
