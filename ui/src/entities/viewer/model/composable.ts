import { readonly, ref } from "vue";

import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";
import { gql } from "@apollo/client/core";

import { convStringToGql } from "~/src/shared/lib";

const queryUserViewer = gqlBuilder.query({
  operation: "userViewer",
  fields: [
    "id",
    "name",
    "email",
  ],
});


const loginMutation = gqlBuilder.mutation({
  operation: "login",
  variables: {
    email: {
      type: "String",
      required: true,
    },
    password: {
      type: "String",
      required: true,
    },
  },
  fields: ["token"],
});

const logoutMutation = gqlBuilder.mutation({
  operation: "logout",
  fields: ["email"],
});


/* GraphQL with VueApollo */
export function useComposable() {

  const { mutate: login } = useMutation(
    convStringToGql(loginMutation.query)
  );

  const { mutate: logout } = useMutation(
    convStringToGql(logoutMutation.query)
  );

  const fetch = (pollInterval: 10000) => {
    return useQuery(convStringToGql(queryUserViewer.query), null, {
      pollInterval,
    });
  };

  return {
    // query
    fetch,

    // mutations
    login,
    logout

  };
}
