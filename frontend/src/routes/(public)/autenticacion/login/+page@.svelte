<script>
    import { user, fetchUser } from "$lib/authStore.js";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";
    import { get } from "svelte/store";

    let email = "";
    let password = "";
    let error = "";
    let loading = false;

    // Si ya está logueado, redirige al home
    onMount(async () => {
        await fetchUser();
        if (get(user)) {
            goto("/");
        }
    });

    async function handleLogin(e) {
        e.preventDefault();
        error = "";
        loading = true;
        try {
            const res = await fetch("/api/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                credentials: "include",
                body: JSON.stringify({ email, password }),
            });
            const data = await res.json();
            if (data.success) {
                await fetchUser();
                goto("/");
            } else {
                error = data.message || "Credenciales incorrectas";
            }
        } catch (err) {
            error = "Error de red o del servidor";
        } finally {
            loading = false;
        }
    }
</script>

<main class="max-w-md mx-auto mt-12 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Iniciar Sesión</h1>
    <form on:submit|preventDefault={handleLogin} class="space-y-4">
        <div>
            <label class="block mb-1 font-semibold" for="email"
                >Correo electrónico</label
            >
            <input
                id="email"
                type="email"
                bind:value={email}
                required
                class="w-full border rounded px-3 py-2"
                autocomplete="username"
            />
        </div>
        <div>
            <label class="block mb-1 font-semibold" for="password"
                >Contraseña</label
            >
            <input
                id="password"
                type="password"
                bind:value={password}
                required
                class="w-full border rounded px-3 py-2"
                autocomplete="current-password"
            />
        </div>
        {#if error}
            <div class="text-red-600">{error}</div>
        {/if}
        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700 transition"
            disabled={loading}
        >
            {#if loading}
                Iniciando...
            {:else}
                Iniciar sesión
            {/if}
        </button>
    </form>
    <div class="mt-4 text-center">
        ¿No tienes cuenta?
        <a href="/autenticacion/registro" class="text-blue-600 hover:underline"
            >Regístrate</a
        >
    </div>
</main>
