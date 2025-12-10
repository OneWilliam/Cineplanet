<script lang="ts">
    import MovieDetail from "./MovieDetail.svelte";
    import type { Pelicula } from "$lib/types";
    import Funciones from "./Funciones.svelte";

    export let data: { pelicula?: Pelicula | null } | undefined;

    const pelicula: Pelicula | null = data?.pelicula ?? null;
</script>

<svelte:head>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap"
    />
    <title
        >{pelicula
            ? `${pelicula.titulo} - Cineplanet`
            : "Película - Cineplanet"}</title
    >
</svelte:head>

<main class="h-max">
    <div class="h-[360px] flex bg-black justify-center items-center">
        TRAILER (placeholder)
    </div>

    <div class="max-w-[1070px] mx-52 px-14">
        {#if pelicula}
            <MovieDetail {pelicula} />
            <Funciones {pelicula} />
        {:else}
            <div class="py-12 text-center text-gray-600">
                No se encontró la película. Intenta volver a
                <a href="/peliculas" class="text-pink-600 underline"
                    >la lista de películas</a
                >.
            </div>
        {/if}
    </div>
</main>
