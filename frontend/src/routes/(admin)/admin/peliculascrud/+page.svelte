<script>
    import Modal from "$lib/components/Modal.svelte";
    import GenericTable from "$lib/components/GenericTable.svelte";

    // --- Lógica del Modal ---
    let showModal = $state(false);

    function handleCloseModal() {
        showModal = false;
    }

    const productColumns = [
        { header: 'ID', field: 'id' },
        { header: 'Nombre del Producto', field: 'nombre' },
        { header: 'Precio', field: 'precio' }, // provisional
    ];
</script>

<main>
    <h1>Tabla Películas / Productos</h1>
    <button on:click={() => showModal = true}>Añadir Película</button>
</main>

<Modal show={showModal} onClose={handleCloseModal} title="Formulario de Creación de Usuario">
    <p>Introduce los datos del nuevo usuario a continuación</p>
    <form>
        <label>
            Nombre: <input type="text" />
        </label>
        <button type="submit">Guardar</button>
    </form>
</Modal>

<GenericTable 
    apiEndpoint="http://localhost/api/productos" 
    columns={productColumns} 
/>
