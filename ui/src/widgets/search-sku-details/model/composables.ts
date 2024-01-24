import { ref } from "vue";
import { di } from "~/src/shared/lib/di";

import { message } from "ant-design-vue";

export const useCsvAnalysisLoader = () => {
  const data = ref([]);

  const service: any = di.container.get(di.TYPES.FeedCsvDetailsRepository);

  const errorText = ref<string>("");

  const fetch = async (
    url: string,
    sku_field_search: string = "89551"
  ): Promise<void> => {
    try {
      data.value = await service.fetchDetailsBySku(url, sku_field_search);

      if (data.value.length > 0) {
        errorText.value = "";
        message.success("Данные обновлены");
      } else {
        errorText.value = "Не найдены данные по этому sku";
        message.error(errorText.value);
      }
    } catch (error) {
      message.error(error);
      errorText.value = error;
    }
  };

  return {
    fetch,
    data,
    errorText,
  };
};
