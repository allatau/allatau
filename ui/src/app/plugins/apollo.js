
import { ApolloClient, createHttpLink, InMemoryCache } from "@apollo/client/core"
import { DefaultApolloClient } from "@vue/apollo-composable"

export default defineNuxtPlugin((nuxtApp) => {
    const token = localStorage.getItem('getUserToken')

    const apolloClient = new ApolloClient({
        uri: "http://127.0.0.1:8000/graphql",
        cache: new InMemoryCache(),
        headers: {
            // your headers
            Authorization: `Bearer ${token}`
        },
        // link: httpLink,

    })
    nuxtApp.vueApp.provide(DefaultApolloClient, apolloClient)
})