<script>
    import { onMount } from "svelte";

    let candies = [];
    let selectedCandies = [];
    let loading = false;

    // Cargar dulces desde el endpoint
    onMount(async () => {
        try {
            loading = true;
            fetch("/api/admin/snack")
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
                        candies = data.data;
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
        } catch (e) {
            console.error(e);
        }
    });

    // Agregar dulce seleccionado
    function addCandy(candy) {
        selectedCandies = [...selectedCandies, candy];
    }
</script>

<h1>Dulcería</h1>

<div class="grid">
    {#each candies as candy}
        <div class="card">
            <h3>{candy.nombre}</h3>
            <p>S/ {candy.precio}</p>
            <button on:click={() => addCandy(candy)}>Agregar</button>
        </div>
    {/each}
</div>

<!-- Lista de seleccionados -->
{#if selectedCandies.length > 0}
    <div class="list">
        <h2>Dulces seleccionados</h2>
        <ul>
            {#each selectedCandies as c}
                <li>{c.nombre} — S/ {c.precio}</li>
            {/each}
        </ul>
    </div>
{/if}

<style>
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .card {
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid #ddd;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    button {
        margin-top: 0.5rem;
        padding: 0.5rem 1rem;
        background: #ff7b00;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 6px;
    }

    button:hover {
        background: #e96f00;
    }

    .list {
        margin-top: 2rem;
        padding: 1rem;
        border: 1px solid #ddd;
        background: #fafafa;
        border-radius: 10px;
    }
</style>
