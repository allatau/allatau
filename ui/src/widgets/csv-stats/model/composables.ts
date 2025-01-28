import { ref } from "vue";
import { di } from "~/src/shared/lib/di";

import { message } from "ant-design-vue";

export const useCsvLoader = () => {
  const data = ref({
    count: 0,
    nonZeroCount: 0,
    nonZeroCountByProducer: 0,
  });

  const service: any = di.container.get(di.TYPES.FeedCsvRepository);

  const errorText = ref<string>("");

  const fetch = async (url: string): Promise<void> => {
    data.value = await service.fetchListBySku(url);

    if (Object.keys(data.value).length > 0) {
      errorText.value = "";
      message.success("Данные обновлены");
    } else {
      errorText.value = "Не найдены данные по этому sku";
      message.error(errorText.value);
    }
  };

  return {
    fetch,
    data,
    errorText,
  };
};
