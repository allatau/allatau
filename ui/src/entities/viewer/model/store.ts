import { ref, computed, toRaw } from "vue";
import { defineStore, acceptHMRUpdate } from "pinia";

export const useStore = defineStore("viewer", () => {
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
