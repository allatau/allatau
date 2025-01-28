import { readonly, ref } from "vue";

import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";
import { gql } from "@apollo/client/core";

import { convStringToGql } from "~/src/shared/lib";

const queryList = gqlBuilder.query({
  operation: "webWidgets",
  fields: [
    "id",
    "name",
    "resource"
  ],
});


const queryItemById = gqlBuilder.query({
  operation: "webWidget",
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

const createWebWidgetMutation = gqlBuilder.mutation({
  operation: "createWebWidget",
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

const deleteWebWidgetMutation = gqlBuilder.mutation({
  operation: "deleteWebWidget",
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
    convStringToGql(createWebWidgetMutation.query)
  );

  const { mutate: deleteItem} = useMutation(
    convStringToGql(deleteWebWidgetMutation.query)
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

  const { result, loading, error } = useQuery(
    convStringToGql(queryList.query),
    null,
    {
      pollInterval: 10000,
    }
  );

  const items = useResult(result, [], (response) => {
    console.log("response", response);
    return response.webWidgets;
  });

  return {
    // query
    fetch,
    fetchById,
    items,

    // mutations
    createItem,
    deleteItem,
  };
}
