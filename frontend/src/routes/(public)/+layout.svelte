<script>
    import "../../app.css";
    import favicon from "$lib/assets/favicon.svg";

    let { children } = $props();
    let showFlyout = $state(false);
    // Desactiva SSR para SPA
    export const ssr = false;
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
            <a href="/autenticacion/login">Iniciar Sesion</a>
            <a href="/autenticacion/registro">Registrarte</a>
        </div>
    {/if}
</nav>
<h1>Public</h1>
{@render children()}
