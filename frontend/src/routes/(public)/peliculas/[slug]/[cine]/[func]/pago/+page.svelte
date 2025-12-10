<script lang="ts">
    import AppStore from "$lib/components/Icons/AppStore.svelte";
    import type { Pelicula } from "$lib/types";

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
            <div>
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold mb-4">{pelicula.titulo}</h1>
                    <p class="text-gray-600 mb-8">{pelicula.descripcion}</p>
                </div>
                <div class="flex flex-col">
                    <h2 class="text-xl font-bold mb-4">Funciones</h2>
                    <ul class="list-disc list-inside">
                        <p>hola</p>
                    </ul>
                </div>
            </div>
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
