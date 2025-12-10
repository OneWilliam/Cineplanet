<script lang="ts">
    import { goto } from "$app/navigation";
    import type { Pelicula } from "$lib/types";

    export let pelicula: Pelicula | null = null;

    const placeholderPoster = "/images/sample_poster.png";

    function handleBuy() {
        if (!pelicula) return;
        goto(`/compra/${pelicula.id}`);
    }
</script>

{#if pelicula}
    <div
        id={`film-detail--${pelicula.id}`}
        class="container--content max-w-[1070px] mx-auto px-4 py-6"
    >
        <div class="film-detail--container">
            <div class="movie-detail-header flex flex-row gap-4 mb-6">
                <div class="flex-1 basis-70">
                    <h1
                        class="movie-detail-header--title text-[50px] font-black leading-tight text-[#004A8C]"
                        style="font-family: 'Montserrat', 'Lato', sans-serif;"
                    >
                        {pelicula.titulo}
                    </h1>

                    <ul
                        class="horizontal-list movie-detail-header--subtitle text-sm text-gray-600 flex items-center gap-2 mt-2"
                    >
                        {#if pelicula.generos?.length}
                            {#each pelicula.generos as genre, idx (idx)}
                                <li class="horizontal-list--item">{genre}</li>
                                {#if idx < pelicula.generos.length - 1}
                                    <li class="horizontal-list--item-separator">
                                        |
                                    </li>
                                {/if}
                            {/each}
                            <li class="horizontal-list--item-separator">|</li>
                        {/if}

                        {#if pelicula.duracion}
                            <li class="horizontal-list--item">
                                {pelicula.duracion}
                            </li>
                            <li class="horizontal-list--item-separator">|</li>
                        {/if}

                        {#if pelicula.classificacion}
                            <li class="horizontal-list--item">
                                {pelicula.classificacion}
                            </li>
                        {/if}
                    </ul>
                </div>

                <div class="movie-detail-header--purchase mt-2 basis-30">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 cursor-pointer bg-[#dc3741] text-white rounded-2xl px-2 py-2 font-semibold shadow-md"
                        aria-label="Comprar entradas"
                        on:click={() => {
                            window.scrollTo({
                                top: 1200,
                                behavior: "smooth",
                            });
                        }}
                    >
                        <span
                            class="icon call-to-action--icon call-to-action--icon_prefix"
                            role="presentation"
                        >
                            <i class="icon cineplanet-icon_tickets"></i>
                        </span>
                        <span class="inline-block text-[14px]">Comprar</span>
                    </button>
                </div>
            </div>

            <div class="relative mt-16 mb-32">
                <section class="movie-details relative w-full min-h-[450px]">
                    <div
                        class="absolute right-0 top-10 h-[530px] w-[400px] bg-[#004A8C] z-0"
                    ></div>

                    <div class="absolute left-[50px] top-[30px] w-[300px] z-30">
                        <div
                            class="rounded-md overflow-hidden shadow-[0_20px_60px_rgba(16,24,40,0.45)]"
                        >
                            <img
                                loading="lazy"
                                src={pelicula.poster?.url ?? placeholderPoster}
                                alt={pelicula.poster?.alt ?? pelicula.titulo}
                                class="w-full h-[450px] object-cover"
                            />
                        </div>
                    </div>

                    <div
                        class="absolute pl-30 pr-10 pt-10 right-5 top-0 h-[550px] w-[400px] bg-[#F6F6F6] p-8 shadow-[0_20px_40px_rgba(16,24,40,0.06)] z-20"
                    >
                        <h2 class="text-2xl font-extrabold text-[#004A8C]">
                            Sinopsis.
                        </h2>
                        <p class="mt-4 text-gray-700 leading-relaxed">
                            {pelicula.sinopsis}
                        </p>

                        <div class="grid grid-row-2 gap-6 mt-8">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">
                                    Idioma
                                </h3>
                                <p class="mt-2">
                                    <span
                                        class="bg-white border-2 border-gray-900 text-gray-900 px-3 py-1 rounded-md font-bold text-xs inline-block"
                                    >
                                        {pelicula.lenguaje ?? "---"}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-gray-900">
                                    Disponible
                                </h3>
                                <p class="mt-2 text-gray-700 text-sm">
                                    {pelicula.formatos?.join(", ") ??
                                        "No disponible"}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
{:else}
    <div class="max-w-[1070px] mx-auto px-4 py-12 text-center">
        <p class="text-gray-600">Cargando información de la película...</p>
    </div>
{/if}
