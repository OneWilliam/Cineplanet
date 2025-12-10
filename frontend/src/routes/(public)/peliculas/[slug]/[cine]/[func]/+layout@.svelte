<script>
    import "../../../../../../app.css";
    import { fetchUser } from "$lib/authStore.js";
    import { onMount } from "svelte";
    import "$lib/fonts/cineplanet-icon.css";

    onMount(() => {
        fetchUser();
    });

    let { children, data } = $props();
</script>

<main class="min-h-[60vh] bg-white">
    <p>Cine: {data.cine}</p>
    <p>Func: {data.func}</p>

    {@render children()}
</main>
