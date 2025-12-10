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
            fetch("/api/user/seat")
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

Hola5
{#if loading}
    <p>Cargando datos de la API...</p>
{:else if error}
    <p style="color: red;">Error al conectar con la API: {error}</p>
{:else}
    <!--de data imprime en json su contenido-->
    <pre id="response">{JSON.stringify(movies, null, 2)}</pre>
{/if}
