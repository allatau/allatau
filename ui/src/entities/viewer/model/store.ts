import { ref, computed, toRaw } from "vue";
import { defineStore, acceptHMRUpdate } from "pinia";

import { useComposable } from "./composable";


export const useStore = defineStore("viewer", () => {
  const { login:loginComp } = useComposable();

  const assignGetUserAuthorized = () => {
    let temp = false;
    console.log(
      "localStorage.getItem",
      localStorage.getItem("getUserAuthorized")
    );
    try {
      const localGetUserAuthorized: any =
        localStorage.getItem("getUserAuthorized");
      temp = Boolean(localGetUserAuthorized);
    } catch (error) {}

    return temp;
  };
  const getUserAuthorized = ref(assignGetUserAuthorized());

  const login = async (email: string, password: string): Promise<void> => {
    try {
      loginComp({email: "dealenx@gmail.com",password: "allatau"});
      // console.log("dataLogin", dataLogin)
      getUserAuthorized.value = true;
      console.log("getUserAuthorized.value", getUserAuthorized.value);
      localStorage.setItem(
        "getUserAuthorized",
        String(getUserAuthorized.value)
      );

      console.log(
        "localStorage.getItem",
        localStorage.getItem("getUserAuthorized")
      );
    } catch (error) {}
  };

  const logout = async (): Promise<void> => {
    try {
      getUserAuthorized.value = false;
      console.log("getUserAuthorized.value", getUserAuthorized.value);
      localStorage.removeItem("getUserAuthorized");
      console.log(
        "localStorage.getItem",
        localStorage.getItem("getUserAuthorized")
      );
    } catch (error) {}
  };

  return {
    getUserAuthorized,
    login,
    logout,
  };
});

// @ts-ignore
if (import.meta.hot) {
  // @ts-ignore
  import.meta.hot.accept(acceptHMRUpdate(useStore, import.meta.hot));
}
