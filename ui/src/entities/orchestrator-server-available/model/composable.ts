import { readonly, ref } from "vue";
import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";

import { convStringToGql } from "~/src/shared/lib";

const queryOrchestratorServerAvailable = gqlBuilder.query({
  operation: "orchestratorServerAvailable",
  fields: ["status"],
});

/* GraphQL with VueApollo */
export function useComposable() {
  const { result, loading, error } = useQuery(
    convStringToGql(queryOrchestratorServerAvailable.query),
    null,
    {
      pollInterval: 1000 * 30,
    }
  );

  const orchestratorServerAvailable = useResult(result, [], (response) => {
    console.log("response", response);
    return response.orchestratorServerAvailable;
  });

  return {
    orchestratorServerAvailable: readonly(orchestratorServerAvailable),
    isLoading: readonly(loading),
    error,
  };
}
