import { readonly, ref } from "vue";

import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";
import { gql } from "@apollo/client/core";

import { convStringToGql } from "~/src/shared/lib";

const queryList = gqlBuilder.query({
  operation: "projects",
  fields: [
    "id",
    "title",
    "description"
  ],
});


const queryItemById = gqlBuilder.query({
  operation: "project",
  variables: {
    id: {
      type: "Int",
      required: true,
    },
  },
  fields: [
    "id",
    "title",
    "description"
  ],
});

const createProjectMutation = gqlBuilder.mutation({
  operation: "createProject",
  variables: {
    title: {
      type: "String",
      required: true,
    },
    description: {
      type: "String",
      required: false,
    },
  },
  fields: ["title", "description"],
});

/* GraphQL with VueApollo */
export function useComposable() {
  const { mutate: create } = useMutation(
    convStringToGql(createProjectMutation.query)
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
    create,
  };
}
