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
    
    <form id="movieForm">
        <label>Título:</label>
        <input type="text" name="title" required /><br /><br />

        <label>Duración:</label>
        <input type="number" name="duration" required /><br /><br />

        <button type="submit">Enviar</button>
    </form>

    <pre id="response"></pre>

    <script>
        // Manejo de formulario
        document
            .getElementById("movieForm")
            .addEventListener("submit", async function (e) {
                e.preventDefault();

                const formData = new FormData(e.target);

                const jsonData = {
                    title: formData.get("title"),
                    duration: formData.get("duration"),
                };

                const res = await fetch("http://localhost/api/admin/movies", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(jsonData),
                });

                const data = await res.json();
                /*document.getElementById("response").textContent =
                    JSON.stringify(data, null, 2);*/
                alert("Película creada con éxito");
            });
    </script>

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
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                {#each data.data as item}
                    <tr>
                        <td>{item.id}</td>
                        <td>{item.title}</td>
                        <td>{item.duration} min</td>
                        <td>
                            <button
                                on:click={async () => {
                                    if (
                                        confirm(
                                            `¿Estás seguro de que deseas eliminar la película "${item.title}"?`,
                                        )
                                    ) {
                                        try {
                                            const res = await fetch(
                                                `http://localhost/api/admin/movies/${item.id}`,
                                                {
                                                    method: "DELETE",
                                                },
                                            );

                                            if (!res.ok) {
                                                throw new Error(
                                                    `Error al eliminar: ${res.status}`,
                                                );
                                            }

                                            alert(
                                                `Película "${item.title}" eliminada con éxito.`,
                                            );

                                            // Recargar la página para actualizar la lista
                                            location.reload();
                                        } catch (e) {
                                            alert(
                                                `Error al eliminar la película: ${e.message}`,
                                            );
                                        }
                                    }
                                }}
                            >
                                Eliminar
                            </button>
                        </td></tr
                    >
                {/each}
            </tbody>
        </table>
    {/if}
</main>
