<script>
    import { onMount } from "svelte";

    // listado de tablas / rutas (nombre mostrado => ruta en backend)
    const tables = [
        { name: "admin", route: "admin" },
        { name: "cinema", route: "cinema" },
        { name: "seat", route: "seat" },
        { name: "receipt", route: "receipt" },
        { name: "category", route: "category" },
        { name: "cinematicket", route: "cinematicket" },
        { name: "city", route: "city" },
        { name: "customer", route: "customer" },
        { name: "cinemapurchase", route: "cinemapurchase" },
        { name: "shoppurchase", route: "shoppurchase" },
        { name: "snack", route: "snack" },
        { name: "shopcategory", route: "shopcategory" },
        { name: "shop", route: "shop" },
        { name: "shopticket", route: "shopticket" },
        { name: "employee", route: "employee" },
        { name: "state", route: "state" },
        { name: "format", route: "format" },
        { name: "screening", route: "screening" },
        { name: "schedule", route: "schedule" },
        { name: "movieformat", route: "movieformat" },
        { name: "room", route: "room" },
        { name: "movie", route: "movie" },
        { name: "LogsHistory", route: "adminlog" }
    ];

    let selected = tables[0].route;
    let data = [];
    let loading = false;
    let error = null;
    let editingRow = null;
    let editFormData = {};
    let deleteLoading = false;

    async function fetchTable(route) {
        loading = true;
        error = null;
        data = [];
        try {
            const res = await fetch(`/api/admin/${route}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const json = await res.json();

            if (json && json.success && json.data) {
                data = json.data;
            } else if (Array.isArray(json)) {
                data = json;
            } else if (json && json.data) {
                data = json.data;
            } else {
                data = Array.isArray(json) ? json : [];
            }
        } catch (e) {
            error = e.message;
        } finally {
            loading = false;
        }
    }

    function select(route) {
        selected = route;
        fetchTable(route);
        const url = new URL(window.location.href);
        url.searchParams.set("t", route);
        history.replaceState(null, "", url.toString());
    }

    onMount(() => {
        // seleccionar desde query param si existe
        try {
            const url = new URL(window.location.href);
            const t = url.searchParams.get("t");
            if (t && tables.find((x) => x.route === t)) selected = t;
        } catch (e) {
            // ignore
        }
        fetchTable(selected);
    });

    function openEditForm(row) {
        editingRow = row;
        editFormData = { ...row };
    }

    function closeEditForm() {
        editingRow = null;
        editFormData = {};
    }

    async function saveEdit() {
        if (!editingRow) return;
        // TODO: implementar PUT /api/admin/{selected}
        console.log("Save edit for", selected, editFormData);
        closeEditForm();
    }

    async function deleteRow(row) {
        if (!confirm(`¿Está seguro de que desea eliminar este registro?`)) return;
        
        deleteLoading = true;
        try {
            // Obtener la primera clave como ID (ajustar según necesidad)
            const idKey = Object.keys(row)[0];
            const idValue = row[idKey];
            
            const res = await fetch(`/api/admin/${selected}`, {
                method: "DELETE",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ [idKey]: idValue, ...row })
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            
            // Recargar la tabla después de eliminar
            await fetchTable(selected);
        } catch (e) {
            alert("Error al eliminar: " + e.message);
        } finally {
            deleteLoading = false;
        }
    }
</script>

<div style="display:flex; gap:16px; padding:12px; font-family:Arial,Helvetica,sans-serif;">
    <!-- Sidebar -->
    <aside style="width:220px; border:1px solid #ccc; padding:8px;">
        <h3>Tables</h3>
        <ul style="list-style:none; padding:0; margin:0;">
            {#each tables as t}
                <li style="margin:4px 0;">
                    <a href="javascript:void(0)" on:click={() => select(t.route)}>{t.name}</a>
                </li>
            {/each}
        </ul>
    </aside>

    <!-- Content -->
    <section style="flex:1; display:flex; flex-direction:column; gap:12px;">
        <div style="border:1px solid #ccc; padding:15px;">
            <h2>Table: {selected}</h2>
            {#if loading}
                <div>Loading...</div>
            {:else if error}
                <div style="color:red;">Error: {error}</div>
            {:else}
                {#if editingRow}
                    <div style="border: 1px solid #0066cc; background-color: #f0f7ff; padding: 15px; margin-bottom: 15px; border-radius: 4px;">
                        <h3>Editar registro</h3>
                        <form on:submit|preventDefault={saveEdit}>
                            <div style="display: grid; gap: 10px;">
                                {#each Object.keys(editFormData) as key}
                                    <div style="display: grid; grid-template-columns: 150px 1fr;">
                                        <label style="font-weight: bold;">{key}</label>
                                        <input
                                            type="text"
                                            value={editFormData[key]}
                                            on:change={(e) => (editFormData[key] = e.target.value)}
                                            style="padding: 6px; border: 1px solid #ccc; border-radius: 3px;"
                                        />
                                    </div>
                                {/each}
                            </div>
                            <div style="display: flex; gap: 8px; margin-top: 12px;">
                                <button type="submit" style="background-color: #0066cc; color: white; padding: 8px 16px; border: none; border-radius: 3px; cursor: pointer;">
                                    Guardar
                                </button>
                                <button type="button" on:click={closeEditForm} style="background-color: #999; color: white; padding: 8px 16px; border: none; border-radius: 3px; cursor: pointer;">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                {/if}

                {#if data.length === 0}
                    <div>No data</div>
                {:else}
                    <div style="overflow:auto;">
                        <table style="border-collapse: collapse; width: 100%; font-size: 14px;">
                            <thead>
                                <tr style="background-color: #f0f0f0; border-bottom: 2px solid #999;">
                                    {#each Object.keys(data[0]) as key}
                                        <th style="padding: 10px 15px; text-align: left; border-right: 1px solid #ddd;">
                                            {key}
                                        </th>
                                    {/each}
                                    <th style="padding: 10px 15px; text-align: left;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {#each data as row}
                                    <tr style="border-bottom: 1px solid #eee;">
                                        {#each Object.keys(data[0]) as key}
                                            <td style="padding: 8px 15px; border-right: 1px solid #eee;">
                                                {row[key]}
                                            </td>
                                        {/each}
                                        <td style="padding: 8px 15px; display: flex; gap: 6px;">
                                            <button
                                                on:click={() => openEditForm(row)}
                                                style="background-color: #0066cc; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;"
                                            >
                                                Modificar
                                            </button>
                                            <button
                                                on:click={() => deleteRow(row)}
                                                disabled={deleteLoading}
                                                style="background-color: #cc0000; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;"
                                            >
                                                {deleteLoading ? "Eliminando..." : "Eliminar"}
                                            </button>
                                        </td>
                                    </tr>
                                {/each}
                            </tbody>
                        </table>
                    </div>
                {/if}
            {/if}
        </div>

        <!-- Right column: raw JSON view -->
        <div style="border:1px solid #ccc; padding:8px; max-height:300px; overflow:auto;">
            <h3>Raw data</h3>
            <pre style="white-space:pre-wrap;">{JSON.stringify(data, null, 2)}</pre>
        </div>
    </section>
</div>
