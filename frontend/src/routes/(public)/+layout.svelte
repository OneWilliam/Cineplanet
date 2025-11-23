<script>
    import "../../app.css";
    import favicon from "$lib/assets/favicon.svg";
    import { user, fetchUser, logout } from "$lib/authStore.js";
    import { onMount } from "svelte";
    import { get } from "svelte/store";

    let { children } = $props();
    let showFlyout = $state(false);
    // Desactiva SSR para SPA
    export const ssr = false;

    // Cargar usuario al montar el layout
    onMount(() => {
        fetchUser();
    });

    function handleLogout() {
        logout();
        showFlyout = false;
    }
</script>

<svelte:head>
    <link rel="icon" href={favicon} />
</svelte:head>
<nav class="border p-4 shadow">
    <a href="/peliculas"> Peliculas </a>
    <button onclick={() => (showFlyout = !showFlyout)}>Cuenta</button>
    {#if showFlyout}
        <div
            class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-10 p-4"
        >
            {#if $user}
                <div class="mb-2">
                    <span class="font-semibold">Hola, {$user.nombre}!</span>
                </div>
                <button
                    class="w-full text-left text-red-600 hover:underline"
                    onclick={handleLogout}
                >
                    Cerrar sesión
                </button>
            {:else}
                <a href="/autenticacion/login">Iniciar Sesion</a>
                <a href="/autenticacion/registro">Registrarte</a>
            {/if}
        </div>
    {/if}
</nav>
<h1>Public</h1>
{@render children()}
