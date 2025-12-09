<script>
    import { onMount } from "svelte";

    let data = null;
    let error = null;
    let isLoading = true;

    const API_URL = "http://localhost/api/admin/movies";

    onMount(async () => {
        try {
            const response = await fetch(API_URL);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            data = await response.json();
        } catch (e) {
            error = e.message;
        } finally {
            isLoading = false;
        }
    });
</script>

<main>
    <h1>PELICULAS (CRUD)</h1>

    {#if isLoading}
        <p>Cargando datos de la API...</p>
    {:else if error}
        <p style="color: red;">Error al conectar con la API: {error}</p>
    {:else}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Duración</th>
                </tr>
            </thead>

            <tbody>
                {#each data.data as item}
                    <tr>
                        <td>{item.id}</td>
                        <td>{item.title}</td>
                        <td>{item.duration} min</td>
                    </tr>
                {/each}
            </tbody>
        </table>
    {/if}
</main>
