
import { ApolloClient, InMemoryCache } from "@apollo/client/core"
import { DefaultApolloClient } from "@vue/apollo-composable"

export default defineNuxtPlugin((nuxtApp) => {
    const apolloClient = new ApolloClient({
        uri: "http://localhost:8000/graphql",
        cache: new InMemoryCache(),
    })
    nuxtApp.vueApp.provide(DefaultApolloClient, apolloClient)
})