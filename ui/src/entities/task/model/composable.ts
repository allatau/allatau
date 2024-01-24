import { readonly, ref } from "vue";

import { useQuery, useResult, useMutation } from "@vue/apollo-composable";
import * as gqlBuilder from "gql-query-builder";
import { gql } from "@apollo/client/core";

import { convStringToGql } from "~/src/shared/lib";

const queryTasks = gqlBuilder.query({
  operation: " tasks",
  fields: [
    "id",
    "name",
    "status",
    "script",
    "files",
    { computing_resource: ["id", "name"] },
  ],
});

const queryTaskJobsByTaskId = gqlBuilder.query({
  operation: "TaskJobsByTaskId",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["json_data"],
});

const queryTaskById = gqlBuilder.query({
  operation: "taskById",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: [
    "id",
    "name",
    "status",
    "script",
    "files",
    { computing_resource: ["id", "name"] },
  ],
});

const createTaskMutation = gqlBuilder.mutation({
  operation: "createTask",
  variables: {
    name: {
      type: "String",
      required: true,
    },
    computing_resource_id: {
      type: "ID",
      required: true,
    },
    script: {
      type: "String",
      required: false,
    },
    computational_model_resource: {
      type: "String",
      required: false,
    },
    converter_service: {
      type: "String",
      required: false,
    },
    numerical_model: {
      type: "String",
      required: false,
    },
  },
  fields: ["id", "name", "script"],
});

const deleteTaskMutation = gqlBuilder.mutation({
  operation: "deleteTask",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id"],
});

const startTaskMutation = gqlBuilder.mutation({
  operation: "startTask",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id"],
});

const checkTaskMutation = gqlBuilder.mutation({
  operation: "checkTask",
  variables: {
    id: {
      type: "ID",
      required: true,
    },
  },
  fields: ["id"],
});

const abortTaskMutation = gqlBuilder.mutation({
  operation: "abortTask",
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
  const { mutate: createTask } = useMutation(
    convStringToGql(createTaskMutation.query)
  );

  const { mutate: deleteTask } = useMutation(
    convStringToGql(deleteTaskMutation.query)
  );

  const { mutate: startTask } = useMutation(
    convStringToGql(startTaskMutation.query)
  );

  const { mutate: checkTask } = useMutation(
    convStringToGql(checkTaskMutation.query)
  );

  const { mutate: abortTask } = useMutation(
    convStringToGql(abortTaskMutation.query)
  );

  const fetch = (pollInterval: 10000) => {
    return useQuery(convStringToGql(queryTasks.query), null, {
      pollInterval,
    });
  };

  const fetchById = (id: string, pollInterval: 10000) => {
    return useQuery(
      convStringToGql(queryTaskById.query),
      () => ({
        id: id,
      }),
      {
        pollInterval,
      }
    );
  };

  const fetchTaskJobsByTaskId = (id: string, pollInterval: 10000) => {
    return useQuery(
      convStringToGql(queryTaskJobsByTaskId.query),
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
    fetchTaskJobsByTaskId,

    // mutations
    createTask,
    deleteTask,
    startTask,
    checkTask,
    abortTask,
  };
}
