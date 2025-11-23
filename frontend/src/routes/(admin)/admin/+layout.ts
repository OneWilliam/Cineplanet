import { redirect } from '@sveltejs/kit';
import { user } from '$lib/authStore.js';
import { get } from 'svelte/store';

export const ssr = false;
export const prerender = false;

export const load = async ({ fetch, url }) => {
    // Si ya tenemos el usuario en memoria, lo usamos
    let currentUser = get(user);

    // Si no, lo pedimos al backend
    if (!currentUser) {
        try {
            const res = await fetch('/api/me', {
                credentials: 'include'
            });
            const data = await res.json();

            if (data.logged_in) {
                user.set(data.user);
                currentUser = data.user;
            } else {
                throw redirect(302, '/autenticacion/login');
            }
        } catch (e) {
            throw redirect(302, '/autenticacion/login');
        }
    }

    // Verificamos el rol
    if (!currentUser || currentUser.rol_nombre?.toLowerCase() !== 'admin' && currentUser.rol_nombre?.toLowerCase() !== 'administrador') {
        // Puedes ajustar el nombre del rol según tu base de datos
        throw redirect(302, '/');
    }

    return { user: currentUser };
};
