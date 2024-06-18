import { readonly, ref } from "vue";
import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";

import { convStringToGql } from "~/src/shared/lib";

const queryComputingResources = gqlBuilder.query({
  operation: "computingResources",
  fields: ["id", "name", "host", "port", "login"],
});

const createComputingResourceMutation = gqlBuilder.mutation({
  operation: "createComputingResource",
  variables: {
    name: {
      type: "String",
      required: true,
    },
    host: {
      type: "String",
      required: true,
    },
    port: {
      type: "String",
      required: true,
    },
    login: {
      type: "String",
      required: true,
    },
    password: {
      type: "String",
      required: true,
    },
  },
  fields: ["id", "name"],
});

const deleteComputingResourceMutation = gqlBuilder.mutation({
  operation: "deleteComputingResource",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id"],
});

/* GraphQL with VueApollo */
export function useComposable() {
  const { result, loading, error } = useQuery(
    convStringToGql(queryComputingResources.query),
    null,
    {
      pollInterval: 10000,
    }
  );

  const computingResources = useResult(result, [], (response) => {
    console.log("response", response);
    return response.computingResources;
  });

  const { mutate: createComputingResource } = useMutation(
    convStringToGql(createComputingResourceMutation.query)
  );

  const { mutate: deleteComputingResource } = useMutation(
    convStringToGql(deleteComputingResourceMutation.query)
  );

  return {
    computingResources: readonly(computingResources),
    isLoading: readonly(loading),
    createComputingResource,
    deleteComputingResource,
    error,
  };
}
