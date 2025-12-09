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

      let itemsPromise = loadData();

  // Función auxiliar para formatear el JSON
  function prettyPrintJson(data) {
    // JSON.stringify(valor, replacer, espacio_de_indentacion)
    // Usamos 'null' como replacer y '2' espacios para la indentación para una lectura clara.
    return JSON.stringify(data, null, 2);
  }
</script>

<main>
  <h1>Datos Raw (en bloque)</h1>

  {#await itemsPromise}
    <p>Cargando datos raw...</p>
  {:then items}
    <!-- Usamos la etiqueta <pre> para preservar el formato -->
    <pre>{prettyPrintJson(items)}</pre>
  {:catch error}
    <p>Ocurrió un error al cargar los datos raw: {error.message}</p>
  {/await}
</main>