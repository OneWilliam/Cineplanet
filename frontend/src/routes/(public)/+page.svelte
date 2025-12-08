<script>
    import { browser } from "$app/environment";
    import { user, fetchUser } from "$lib/authStore.js";
    import { onMount } from "svelte";

    /** @type {any[]} */
    let movies = $state([]);
    let loading = $state(true);
    let error = $state(null);
    // Cargar usuario al montar la página
    onMount(() => {
        fetchUser();
    });

    /**
     * Tipo de retorno
     * @param {any} u
     * @returns {string}
     */
    function getName(u) {
        try {
            return u && u.nombre ? u.nombre : "";
        } catch (e) {
            return "";
        }
    }

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

<!-- //px-4 sm:px-6 lg:px-8 -->
<main class=" w-full">
    <!-- <Hero items={movies.slice(0, 6)} /> -->
    <h1 class="text-2xl h-[700px] bg-black font-bold mb-4 w-full">HERO</h1>
    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-4">Cartelera de Cine</h1>
        {#if $user}
            <div class="mb-4 text-green-700 font-semibold">
                ¡Bienvenido, {getName($user)}!
            </div>
        {/if}

        <h2>
            <a href="/peliculas"> Películas </a>
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
                        <h2 class="text-lg font-semibold">{movie.titulo}</h2>
                        <p class="text-sm mt-2">
                            <strong>Duración:</strong>
                            {movie.duracion} min
                        </p>
                    </div>
                {/each}
            </div>
        {/if}

        <h1 class="text-2xl h-[900px] font-bold mb-4">CONTENIDO</h1>
        <hr class="my-8" />
    </div>
</main>
