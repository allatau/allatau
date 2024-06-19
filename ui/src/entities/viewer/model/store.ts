import { ref, computed, toRaw } from "vue";
import { defineStore, acceptHMRUpdate } from "pinia";

import { useComposable } from "./composable";

import axios from "axios"


export const useStore = defineStore("viewer", () => {
  const { login:loginComp } = useComposable();

  const assignGetUserAuthorized = () => {
    let temp = false;
    try {
      const localGetUserAuthorized: any =
        localStorage.getItem("getUserAuthorized");
      temp = Boolean(localGetUserAuthorized);
    } catch (error) {}

    return temp;
  };

  const assignGetUserToken = () => {
    let temp = "";

    try {
      const localGetUserToken: any =
        localStorage.getItem("getUserToken");
      temp = localGetUserToken;
    } catch (error) {}

    return temp;
  };

  const getUserAuthorized = ref(assignGetUserAuthorized());
  const token = ref(assignGetUserToken());

  const login = async (email: string, password: string): Promise<void> => {
    console.log(1)
    let loginParams = {
      email: email,
      password: password
    }

    try {
      const data: any = await loginComp(loginParams as Object);

      token.value = data.data.login.token as any

      getUserAuthorized.value = true;
  
      localStorage.setItem(
        "getUserAuthorized",
        String(getUserAuthorized.value)
      );
      
      console.log("token.value", token.value);
      
      localStorage.setItem(
        "getUserToken",
        String(token.value)
      );
    } catch (error) {
      console.log("error", error)
      getUserAuthorized.value = false;
    }
  };

  const logout = async (): Promise<void> => {
    try {
      getUserAuthorized.value = false;
      localStorage.removeItem("getUserAuthorized");
    } catch (error) {}
  };

  return {
    getUserAuthorized,
    token,
    login,
    logout,
  };
});

// @ts-ignore
if (import.meta.hot) {
  // @ts-ignore
  import.meta.hot.accept(acceptHMRUpdate(useStore, import.meta.hot));
}
