import { ref, computed, toRaw } from "vue";
import { defineStore, acceptHMRUpdate } from "pinia";
import { di } from "~/src/shared/lib/di";

import { FeedConfigDTO, FeedConfigModel } from "~/src/shared/types";

export const useStore = defineStore("feed", () => {
  const dict = ref<FeedConfigDTO>({});
  const list = ref<FeedConfigModel[]>([]);
  const service: any = di.container.get(di.TYPES.FeedService);

  const fetch = async (): Promise<void> => {
    try {
      await service.fetchData();
      dict.value = service.getFeedsDict();
      list.value = service.getFeeds();
    } catch (error) {}
  };

  const getById = (id: string) => {
    let item = null;

    try {
      item = toRaw(dict.value[id]) as FeedConfigModel;
    } catch (error) {}

    return item;
  };

  return {
    fetch,
    getById,
    dict: computed(() => dict),
    list: computed(() => list),
  };
});

// @ts-ignore
if (import.meta.hot) {
  // @ts-ignore
  import.meta.hot.accept(acceptHMRUpdate(useStore, import.meta.hot));
}
