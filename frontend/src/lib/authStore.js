import { writable } from "svelte/store";

// Store para guardar los datos del usuario autenticado
export const user = writable(null);

// Store para indicar si se está verificando la sesión (útil para mostrar spinners)
export const isChecking = writable(true);

// Función para cargar el usuario desde la API de sesión
export async function fetchUser() {
  isChecking.set(true);
  try {
    const res = await fetch("/api/me", {
      credentials: "include",
    });
    const data = await res.json();
    if (data.logged_in) {
      user.set(data.user);
    } else {
      user.set(null);
    }
  } catch (e) {
    user.set(null);
  } finally {
    isChecking.set(false);
  }
}

// Función para cerrar sesión (logout)
export async function logout() {
  await fetch("/api/user/logout", {
    method: "POST",
    credentials: "include",
  });
  user.set(null);
}
