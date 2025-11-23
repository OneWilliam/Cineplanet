<script>
    import "../app.css";
    import favicon from "$lib/assets/favicon.svg";

    let { children } = $props();

    // Desactiva SSR para SPA
    export const ssr = false;
</script>

<svelte:head>
    <link rel="icon" href={favicon} />
</svelte:head>

{@render children()}
