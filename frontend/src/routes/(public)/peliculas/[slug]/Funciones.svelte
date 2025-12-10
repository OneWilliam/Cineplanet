<script lang="ts">
    import { goto } from "$app/navigation";
    import type { Pelicula, cinemaFunciones, funcion } from "$lib/types";
    import {
        getCitiesForMovie,
        getCinemasForMovieAndCity,
        getNextNDatesForSelect,
    } from "$lib/data/showtimes";

    export let pelicula: Pelicula | null = null;

    // UI selections / state
    let selectedCity: string = "Dónde estás";
    let selectedCinemaId: string = "Elige tu Cineplanet";
    let selectedDate: string = "";
    let cities: string[] = [];
    let cinemas: cinemaFunciones[] = [];

    // track which cinema sections are open
    let openCinemas: Record<string, boolean> = {};

    // A few derived variables to populate selects and lists
    $: if (pelicula) {
        cities = ["Dónde estás", ...getCitiesForMovie(pelicula.id)];
        // initialize date with the first available date label
        const dates = getNextNDatesForSelect(4);
        selectedDate = selectedDate || dates[0]?.value || "";
        // Set cinemas according to the selected city (or all if none)
        cinemas = getCinemasForMovieAndCity(
            pelicula.id,
            selectedCity === "Dónde estás" ? undefined : selectedCity,
        );
    } else {
        cities = [];
        cinemas = [];
        selectedCity = "Dónde estás";
    }

    // When the city changes, reset the selected cinema id
    $: if (selectedCity) {
        // if current cinemas do not contain the selected cinema id, reset the selected cinema
        const ids = cinemas.map((c) => c.id);
        if (!ids.includes(selectedCinemaId)) {
            selectedCinemaId = "Elige tu Cineplanet";
        }
    }

    // derived filtered list of cinemas for rendering (applies an explicit cinema filter)
    $: filteredCinemas =
        selectedCinemaId !== "Elige tu Cineplanet" && selectedCinemaId
            ? cinemas.filter((c) => c.id === selectedCinemaId)
            : cinemas;

    // date options (static small list for the dropdown)
    const dateOptions = getNextNDatesForSelect(4);

    function toggleOpen(cinemaId: string) {
        openCinemas[cinemaId] = !openCinemas[cinemaId];
        // Reassign the object to trigger Svelte reactivity when a property changes
        openCinemas = { ...openCinemas };
    }

    function bookingUrlForShowtime(cinema: cinemaFunciones, showtime: funcion) {
        // Build a route that maps to the slug/cine/func route
        // This uses the existing pattern /peliculas/:slug/:cine/:func. If slug isn't set, use the id.
        const slugOrId = pelicula?.slug ?? pelicula?.id;
        return `/peliculas/${slugOrId}/${cinema.id}/${showtime.id}/asientos`;
    }

    function bookShowtime(cinema: cinemaFunciones, showtime: funcion) {
        if (!pelicula) return;
        if (showtime.lleno) return;
        goto(bookingUrlForShowtime(cinema, showtime));
    }

    function seatsAvailableMessage(st: funcion) {
        const seats =
            typeof st.sitiosDisponibles === "number"
                ? st.sitiosDisponibles
                : "N/A";
        // In a full app we'd show a flyout/popover; for the mock, a simple alert is OK for testing
        alert(`Asientos disponibles: ${seats}`);
    }
</script>

{#if pelicula}
    <div class="mt-40 mb-20">
        <h1
            class="movie-detail-header--title text-[40px] font-black leading-tight text-[#004A8C]"
            style="font-family: 'Montserrat', 'Lato', sans-serif;"
        >
            La función perfecta para ti.
        </h1>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mt-6">
            <!-- City selector -->
            <div class="w-full sm:w-auto">
                <label for="select-city" class="text-sm block mb-2"
                    >Por ciudad</label
                >
                <select
                    id="select-city"
                    bind:value={selectedCity}
                    class="dropdown--select px-4 py-2 rounded border focus:outline-none"
                    aria-label="Por ciudad"
                >
                    <option value="Dónde estás">Dónde estás</option>
                    {#each cities as city (city)}
                        {#if city !== "Dónde estás"}
                            <option value={city}>{city}</option>
                        {/if}
                    {/each}
                </select>
            </div>

            <!-- Cinema selector -->
            <div class="w-full sm:w-auto">
                <label for="select-cinema" class="text-sm block mb-2"
                    >Por cine</label
                >
                <select
                    id="select-cinema"
                    bind:value={selectedCinemaId}
                    class="dropdown--select px-4 py-2 rounded border focus:outline-none"
                    aria-label="Por cine"
                >
                    <option value="Elige tu Cineplanet"
                        >Elige tu Cineplanet</option
                    >
                    {#each cinemas as c (c.id)}
                        <option value={c.id}>{c.nombre}</option>
                    {/each}
                </select>
            </div>

            <!-- Date selector -->
            <div class="w-full sm:w-auto">
                <label for="select-date" class="text-sm block mb-2"
                    >Por fecha</label
                >
                <select
                    id="select-date"
                    bind:value={selectedDate}
                    class="dropdown--select px-4 py-2 rounded border focus:outline-none"
                    aria-label="Por fecha"
                >
                    {#each dateOptions as d}
                        <option value={d.value}>{d.label}</option>
                    {/each}
                </select>
            </div>
        </div>

        <!-- Showtimes list -->
        <div class="mt-6">
            {#if filteredCinemas.length === 0}
                <div class="text-gray-600 py-6 border rounded-md px-4">
                    No hay funciones para esta selección.
                </div>
            {/if}

            {#each filteredCinemas as cinema (cinema.id)}
                <div class="bg-[#F6F6F6] overflow-hidden">
                    <button
                        type="button"
                        class="w-full text-left flex items-center justify-between p-4 hover:bg-gray-50"
                        on:click={() => toggleOpen(cinema.id)}
                        aria-expanded={openCinemas[cinema.id]
                            ? "true"
                            : "false"}
                    >
                        <div class="flex items-center gap-4">
                            <div class="cinema-showcases--summary-content">
                                <h3
                                    class="cinema-showcases--summary-name text-lg font-semibold"
                                >
                                    {cinema.nombre}
                                </h3>
                            </div>
                        </div>

                        <div
                            class="cinema-showcases--summary-toggle-icon text-gray-600"
                        >
                            {#if openCinemas[cinema.id]}
                                <span class="text-sm">−</span>
                            {:else}
                                <span class="text-sm">+</span>
                            {/if}
                        </div>
                    </button>

                    {#if openCinemas[cinema.id]}
                        <div class="px-4 pb-4">
                            <div class="cinema-showcases--details mt-2">
                                {#each cinema.sesiones as session (session.id)}
                                    <div class="sessions-details py-3">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="border-black border font-bold rounded-2 bg-gray-100 px-2 py-1 rounded text-xs"
                                            >
                                                {session.dimension}
                                            </span>
                                            <span
                                                class="sessions-details--formats-theather text-sm font-medium"
                                            >
                                                {session.cine}
                                            </span>
                                            <span
                                                class="sessions-details--formats-language text-sm text-gray-600"
                                            >
                                                {session.idioma}
                                            </span>
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-3">
                                            {#each session.funciones as st (st.id)}
                                                <div
                                                    class="showtime-selector overflow-hidden sessions-details--session-item flex items-center border rounded-2xl"
                                                >
                                                    <button
                                                        id={"button-ui-" +
                                                            st.id}
                                                        class="px-3 py-1 bg-[#004A8C] text-white disabled:bg-gray-200 disabled:text-gray-500"
                                                        disabled={st.lleno}
                                                        on:click={() =>
                                                            bookShowtime(
                                                                cinema,
                                                                st,
                                                            )}
                                                        aria-disabled={st.lleno}
                                                        title={st.lleno
                                                            ? "Función no disponible"
                                                            : `Comprar entrada ${st.hora}`}
                                                    >
                                                        {st.hora}
                                                    </button>
                                                    <div
                                                        class="w-px bg-black h-full"
                                                    ></div>
                                                    <button
                                                        class="p-1 rounded text-gray-600 hover:text-[#004A8C]"
                                                        on:click={() =>
                                                            seatsAvailableMessage(
                                                                st,
                                                            )}
                                                        title="Ver asientos disponibles"
                                                        aria-label="Detalles de asientos"
                                                    >
                                                        <i
                                                            class="icon cineplanet-icon cineplanet-icon_seats cineplanet-icon_medium"
                                                        ></i>
                                                    </button>
                                                </div>
                                            {/each}
                                        </div>
                                    </div>
                                {/each}
                            </div>
                        </div>
                    {/if}
                </div>
            {/each}
        </div>
    </div>
{:else}
    <div class="max-w-[1070px] mx-auto px-4 py-12 text-center">
        <p class="text-gray-600">Cargando información de la película...</p>
    </div>
{/if}
