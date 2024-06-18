import { readonly, ref } from "vue";

import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";
import { gql } from "@apollo/client/core";

import { convStringToGql } from "~/src/shared/lib";

const queryList = gqlBuilder.query({
  operation: "microservices",
  fields: [
    "id",
    "name",
    "resource"
  ],
});


const queryItemById = gqlBuilder.query({
  operation: "microservice",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: [
    "id",
    "name",
    "resource"
  ],
});

const createMicroserviceMutation = gqlBuilder.mutation({
  operation: "createMicroservice",
  variables: {
    name: {
      type: "String",
      required: true,
    },
    resource: {
      type: "String",
      required: true,
    },
  },
  fields: ["name", "resource"],
});

const deleteMicroserviceMutation = gqlBuilder.mutation({
  operation: "deleteMicroservice",
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
  const { mutate: createItem } = useMutation(
    convStringToGql(createMicroserviceMutation.query)
  );

  const { mutate: deleteItem} = useMutation(
    convStringToGql(deleteMicroserviceMutation.query)
  );

  const fetch = (pollInterval: 10000) => {
    return useQuery(convStringToGql(queryList.query), null, {
      pollInterval,
    });
  };

  const fetchById = (id: string, pollInterval: 10000) => {
    return useQuery(
      convStringToGql(queryItemById.query),
      () => ({
        id: id,
      }),
      {
        pollInterval,
      }
    );
  };

  return {
    // query
    fetch,
    fetchById,

    // mutations
    createItem,
    deleteItem,
  };
}
