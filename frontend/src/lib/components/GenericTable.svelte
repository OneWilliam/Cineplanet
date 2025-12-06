<script>
    // import { $props, $state, $effect } from 'svelte';

    let { 
        apiEndpoint = '', 
        columns = [] 
    } = $props();
    
    let products = $state([]); // Aquí se almacenan los datos
    let isLoading = $state(true);
    let error = $state(null);

    async function loadProducts() {
        isLoading = true;
        try {
            const response = await fetch(apiEndpoint);
            if (!response.ok) {
                throw new Error("Error al cargar los datos: " + response.statusText);
            }
            products = await response.json();
            error = null;
        } catch (err) {
            error = err.message;
        } finally {
            isLoading = false;
        }
    }

    $effect(() => {
        loadProducts();
    });
</script>

<div class="table-container">
    {#if isLoading}
        <p>Cargando datos...</p>
    {:else if error}
        <p class="error">Se produjo un error: {error}</p>
    {:else if products.length === 0}
        <p>No se encontraron registros.</p>
    {:else}
        <table>
            <thead>
                <tr>
                    {#each columns as column}
                        <th>{column.header}</th>
                    {/each}
                </tr>
            </thead>
            <tbody>
                {#each products as product (product.id)}
                    <tr>
                        {#each columns as column}
                            <td>{product[column.field]}</td> 
                        {/each}
                    </tr>
                {/each}
            </tbody>
        </table>
    {/if}
</div>

<style>
    .table-container { padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .error { color: red; font-weight: bold; }
</style>
