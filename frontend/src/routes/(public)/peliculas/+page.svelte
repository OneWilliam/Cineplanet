<script lang="ts">
    import { getMovies } from "$lib/data/movies";
    import type { Pelicula } from "$lib/types";

    const peliculas: Pelicula[] = getMovies();
</script>

<svelte:head>
    <title>Películas - Cineplanet</title>
</svelte:head>

<main class="py-8">
    <div class="max-w-[1070px] mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold">Películas</h1>
            <p class="text-sm text-gray-600">
                Mostrando {peliculas.length} películas
            </p>
        </div>

        {#if peliculas.length === 0}
            <div class="text-center py-12 text-gray-700">
                No hay películas disponibles.
            </div>
        {:else}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {#each peliculas as pelicula (pelicula.id)}
                    <a
                        href={`/peliculas/${pelicula.id}`}
                        class="block bg-white rounded-md shadow hover:shadow-lg overflow-hidden group"
                        aria-label={`Ver detalles de ${pelicula.titulo}`}
                    >
                        <div class="relative w-full aspect-2/3 bg-gray-100">
                            <img
                                src={pelicula.poster?.url ??
                                    "/images/sample_poster.png"}
                                alt={pelicula.poster?.alt ?? pelicula.titulo}
                                class="object-cover w-full h-full"
                                loading="lazy"
                            />
                        </div>

                        <div class="p-4">
                            <h2
                                class="text-lg font-semibold text-gray-900 group-hover:text-pink-600"
                            >
                                {pelicula.titulo}
                            </h2>

                            <div
                                class="mt-1 text-sm text-gray-600 flex items-center gap-2"
                            >
                                {#if pelicula.generos?.length}
                                    <span>{pelicula.generos.join(", ")}</span>
                                    <span class="text-gray-300">|</span>
                                {/if}
                                {#if pelicula.duracion}
                                    <span>{pelicula.duracion}</span>
                                    <span class="text-gray-300">|</span>
                                {/if}
                                {#if pelicula.classificacion}
                                    <span>{pelicula.classificacion}</span>
                                {/if}
                            </div>

                            <p class="mt-3 text-sm text-gray-700 line-clamp-3">
                                {pelicula.sinopsis ??
                                    "Sin sinopsis disponible."}
                            </p>
                        </div>
                    </a>
                {/each}
            </div>
        {/if}
    </div>
</main>
