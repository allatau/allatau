import { readonly, ref } from "vue";

import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";
import { gql } from "@apollo/client/core";

import { convStringToGql } from "~/src/shared/lib";

const queryList = gqlBuilder.query({
  operation: "calculationCases",
  fields: ["id", "name", { file: ["id", "name"] }],
});

const queryItemById = gqlBuilder.query({
  operation: "calculationCase",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id", "name", { file: ["id", "name"] }],
});

const createMutation = gqlBuilder.mutation({
  operation: "createCalculationCase",
  variables: {
    name: {
      type: "String",
      required: true,
    },
    file_id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["name"],
});

const deleteMutation = gqlBuilder.mutation({
  operation: "deleteCalculationCase",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id"],
});

const queryOneItem = gqlBuilder.query({
  operation: "calculationCase",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id", "name", "meta", { file: ["id", "name"] }],
});

const updateMutation = gqlBuilder.mutation({
  operation: "updateCalculationCase",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
    meta: {
      type: "String",
      required: false,
    },
  },
  fields: ["id", "name", "meta"],
});

/* GraphQL with VueApollo */
export function useComposable() {
  const { mutate: createItem } = useMutation(
    convStringToGql(createMutation.query)
  );

  const { mutate: deleteItem } = useMutation(
    convStringToGql(deleteMutation.query)
  );

  const { mutate: updateItem } = useMutation(
    convStringToGql(updateMutation.query)
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

  const fetchOne = (id: string) => {
    const { result, loading } = useQuery(
      convStringToGql(queryOneItem.query),
      { id },
      {
        pollInterval: 2000,
      }
    );

    const data = useResult(result, null, (response) => {
      console.log("Ответ от сервера:", response);
      return response.calculationCase;
    });

    return {
      result: data,
      loading,
    };
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
    return response.calculationCases;
  });

  const update = async (id: string, data: { meta?: string }) => {
    return await updateItem({
      id,
      ...data,
    });
  };

  return {
    // query
    fetch,
    fetchById,
    items,

    // mutations
    createItem,
    deleteItem,
    fetchOne,
    update,
  };
}
