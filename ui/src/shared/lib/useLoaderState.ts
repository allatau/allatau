const dataStates = {
  notAsked: "notAsked",
  loading: "loading",
  loaded: "loaded",
  failed: "failed",
};

export const useLoaderState = () => {
  const errorText = ref("");
  const dataState = ref(dataStates.notAsked);

  const isLoading = computed(() => {
    return toRaw(dataState.value) === dataStates.loading;
  });

  const isLoaded = computed(() => {
    return dataState.value === dataStates.loaded;
  });

  const isError = computed(() => {
    return dataState.value === dataStates.failed;
  });

  const enableLoading = () => {
    dataState.value = dataStates.loading;
    errorText.value = "";
  };

  const enableError = (text: string) => {
    dataState.value = dataStates.failed;
    errorText.value = text;
  };
  const enableLoaded = () => {
    dataState.value = dataStates.loaded;
    errorText.value = "";
  };

  onMounted(() => {
    console.log("isError", isError);
  });

  return {
    errorText: toRaw(errorText),
    dataState: toRaw(dataState),
    isLoading,
    isLoaded,
    isError,
    enableLoading,
    enableError,
    enableLoaded,
  };
};
