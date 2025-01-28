import { gql } from "@apollo/client/core";

export const convStringToGql = (value) => {
  return gql`
    ${value}
  `;
};
