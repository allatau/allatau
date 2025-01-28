import { ViewerModel } from "~/src/entities/viewer";

export default defineNuxtRouteMiddleware((to, from) => {
  const route: any = useRoute();
  console.log("route", route);

  const { getUserAuthorized } = ViewerModel.useStore();

  setTimeout(() => {
    console.log("getUserAuthorized", getUserAuthorized);
    if (getUserAuthorized === false) {
      navigateTo("/auth");
    }
    if (getUserAuthorized === true) {
      if (route.href === "/auth") {
        navigateTo("/");
      }
    }
  }, 100);
});
