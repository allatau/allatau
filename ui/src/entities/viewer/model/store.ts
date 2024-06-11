import { ref, computed, toRaw } from "vue";
import { defineStore, acceptHMRUpdate } from "pinia";

import { useComposable } from "./composable";

import axios from "axios"


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

  const assignGetUserToken = () => {
    let temp = "";
    console.log(
      "localStorage.getItem",
      localStorage.getItem("getUserToken")
    );
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
    let loginParams = JSON.stringify({
      "email": "dealenx@gmail.com",
      "password": "allatau"
    });

    try {

      // loginComp({email: "dealenx@gmail.com",password: "allatau"});
      const loginResponse  = await axios.request({
        method: 'post',
        maxBodyLength: Infinity,
        url: 'http://localhost:8000/api/login',
        headers: { 
          'Content-Type': 'application/json'
        },
        data : loginParams
      } as any)

      console.log("loginResponse.data.data.token", loginResponse.data.data.token)

      token.value = loginResponse.data.data.token

      getUserAuthorized.value = true;
  
      localStorage.setItem(
        "getUserAuthorized",
        String(getUserAuthorized.value)
      );
      

      localStorage.setItem(
        "getUserToken",
        String(token.value)
      );


      console.log(
        "localStorage.getItem",
        localStorage.getItem("getUserAuthorized")
      );
    } catch (error) {
      getUserAuthorized.value = false;
    }
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
