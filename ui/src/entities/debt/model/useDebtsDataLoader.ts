import { message } from "ant-design-vue";
import axios from "axios";

const loadMirInstrumentaData = async () => {
  let config = {
    method: "get",
    url: "https://share-trout-51.deno.dev/mir-instrumenta",
  };

  try {
    const response = await axios(config);

    return response.data.value;
  } catch (error) {
    message.error("Что-то пошломалась");
  }
};

const loadSamsonNskData = async () => {
  let config = {
    method: "get",
    url: "https://share-trout-51.deno.dev/samson-nsk",
  };

  try {
    const response = await axios(config);

    return response.data.value;
  } catch (error) {
    message.error("Что-то пошломалась");
  }
};

const loadSamsonVrnData = async () => {
  let config = {
    method: "get",
    url: "https://share-trout-51.deno.dev/samson-vrn",
  };

  try {
    const response = await axios(config);

    return response.data.value;
  } catch (error) {
    message.error("Что-то пошломалась");
  }
};

export const useDebtsDataLoader = () => {
  const jsonData = ref([] as any);
  const debMirInstrumenta = ref(0);
  const debSamsonNsk = ref(0);
  const debSamsonVrn = ref(0);
  const load = async () => {
    const mirInstrumentaDataValue = await loadMirInstrumentaData();
    debMirInstrumenta.value = mirInstrumentaDataValue;

    const samsonNskDataValue = await loadSamsonNskData();
    debSamsonNsk.value = parseFloat(
      samsonNskDataValue.replace(/\s/g, "").replace(",", ".")
    );

    const samsonVrnDataValue = await loadSamsonVrnData();
    debSamsonVrn.value = parseFloat(
      samsonVrnDataValue.replace(/\s/g, "").replace(",", ".")
    );

    jsonData.value = [
      {
        id: 1,
        name: "Мир инструмента",
        deb: debMirInstrumenta.value,
      },
      {
        id: 2,
        name: "Самсон Новосибирск",
        deb: debSamsonNsk.value,
      },
      {
        id: 3,
        name: "Самсон Воронеж",
        deb: debSamsonVrn.value,
      },
    ];
  };

  const getById = (id: string) => {
    let item = null;

    try {
      // @ts-ignore
      item = toRaw(jsonData.value[id]) as any;
    } catch (error) {}

    return item;
  };

  return {
    load,
    jsonData,
    getById,
  };
};
