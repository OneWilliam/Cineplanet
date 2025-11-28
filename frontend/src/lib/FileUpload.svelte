<script>
    import { browser } from "$app/environment";

    let loading = $state(false);
    let error = $state(null);
    let successMessage = $state(null);
    let uploadedFilePath = $state(null);

    let existingImages = $state([]);
    let loadingGallery = $state(true);

    async function fetchImages() {
        if (!browser) return;
        loadingGallery = true;
        try {
            const response = await fetch("/api/admin/uploads", {
                credentials: "include",
            });
            const data = await response.json();
            if (data.success) {
                existingImages = data.data;
            }
        } catch (e) {
            // Silently fail or add a specific error state for the gallery
            console.error("Error al cargar la galería:", e);
        } finally {
            loadingGallery = false;
        }
    }

    async function handleSubmit(event) {
        event.preventDefault();
        const file = event.target.elements.imageFile.files[0];

        if (!file) {
            error = "Por favor, selecciona una imagen para subir.";
            return;
        }

        loading = true;
        error = null;
        successMessage = null;
        uploadedFilePath = null;

        const formData = new FormData();
        formData.append("image", file);

        try {
            const response = await fetch("/api/admin/upload", {
                method: "POST",
                body: formData,
                credentials: "include",
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || "Ocurrió un error en el servidor.",
                );
            }

            successMessage = data.message;
            uploadedFilePath = data.filePath;

            // Refresh the gallery after successful upload
            fetchImages();
        } catch (e) {
            error = e.message;
        } finally {
            loading = false;
        }
    }

    // Fetch initial images when component loads
    $effect(() => {
        fetchImages();
    });
</script>

<div class="my-8 p-6 border rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Subir Imagen</h2>
    <form onsubmit={handleSubmit}>
        <div class="mb-4">
            <input
                type="file"
                name="imageFile"
                accept="image/*"
                class="block w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-full file:border-0
                       file:text-sm file:font-semibold
                       file:bg-violet-50 file:text-violet-700
                       hover:file:bg-violet-100"
            />
        </div>
        <button
            type="submit"
            disabled={loading}
            class="px-4 py-2 bg-blue-600 text-white rounded-md disabled:bg-gray-400"
        >
            {#if loading}
                Subiendo...
            {:else}
                Subir
            {/if}
        </button>
    </form>

    {#if error}
        <div class="mt-4 text-red-600 bg-red-100 p-3 rounded-md">
            <strong>Error:</strong>
            {error}
        </div>
    {/if}

    {#if successMessage}
        <div class="mt-4 text-green-700 bg-green-100 p-3 rounded-md">
            <p>{successMessage}</p>
            {#if uploadedFilePath}
                <p>Ruta del archivo: <code>{uploadedFilePath}</code></p>
                <img
                    src={uploadedFilePath}
                    alt="Imagen subida"
                    class="mt-2 max-w-xs rounded shadow"
                />
            {/if}
        </div>
    {/if}
</div>

<div class="my-8">
    <h2 class="text-xl font-bold mb-4">Galería de Imágenes</h2>
    {#if loadingGallery}
        <p>Cargando galería...</p>
    {:else if existingImages.length === 0}
        <p>No hay imágenes subidas todavía.</p>
    {:else}
        <div
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
        >
            {#each existingImages as imageUrl}
                <div
                    class="aspect-square bg-gray-100 rounded-lg overflow-hidden"
                >
                    <img
                        src={imageUrl}
                        alt="Imagen subida"
                        class="w-full h-full object-cover"
                    />
                </div>
            {/each}
        </div>
    {/if}
</div>
