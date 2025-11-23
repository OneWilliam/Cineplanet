<script>
    import { browser } from "$app/environment";
    import { user, fetchUser } from "$lib/authStore.js";
    import { onMount } from "svelte";

    let movies = $state([]);
    let loading = $state(true);
    let error = $state(null);
    let { data } = $props();

    // Cargar usuario al montar la página
    onMount(() => {
        fetchUser();
    });

    $effect(() => {
        // Ensure this runs only in the browser
        if (browser) {
            loading = true;
            fetch("/api/movies")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            `HTTP ${response.status}: ${response.statusText}`,
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        movies = data.data;
                    } else {
                        throw new Error(
                            data.message ||
                                "La API no retornó un estado de éxito.",
                        );
                    }
                })
                .catch((e) => {
                    error = e.message;
                })
                .finally(() => {
                    loading = false;
                });
        }
    });
</script>

<main class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Cartelera de Cine</h1>
    {#if $user}
        <div class="mb-4 text-green-700 font-semibold">
            ¡Bienvenido, {$user.nombre}!
        </div>
    {/if}
    <h2>
        <a href="/peliculas"> Peliculas </a>
    </h2>
    {#if loading}
        <p>Cargando películas...</p>
    {:else if error}
        <div class="text-red-500">
            <p>Hubo un error al cargar la cartelera:</p>
            <pre>{error}</pre>
        </div>
    {:else if movies.length === 0}
        <p>No hay películas en cartelera en este momento.</p>
    {:else}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
        >
            {#each movies as movie}
                <div class="border rounded-lg p-4 shadow">
                    <h2 class="text-lg font-semibold">{movie.title}</h2>
                    <p class="text-sm mt-2">
                        <strong>Duración:</strong>
                        {movie.duration} min
                    </p>
                </div>
            {/each}
        </div>
    {/if}

    <hr class="my-8" />
</main>
