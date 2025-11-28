<script>
    import Cineplanet from "./Icons/Cineplanet.svelte";
    import CineplanetColor from "./Icons/CineplanetColor.svelte";
    import { user, logout } from "$lib/authStore.js";

    let showAccountFlyout = false;
    let showMobileMenu = false;
    // Castear el tipo a any
    $: currentUser = /** @type {any} */ $user;

    function toggleAccount() {
        showAccountFlyout = !showAccountFlyout;
    }

    function handleLogout() {
        logout();
        showAccountFlyout = false;
        showMobileMenu = false;
    }

    function toggleMobileMenu() {
        showMobileMenu = !showMobileMenu;
    }

    // Helper para el nombre
    /** @param {any} u */
    function getName(u) {
        try {
            return u && u.nombre ? u.nombre : "";
        } catch (e) {
            return "";
        }
    }
</script>

<header
    class="fixed top-0 left-0 w-full z-50 bg-linear-to-b from-black/50 to-transparent shadow-md"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-cesnter justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-4">
                <a href="/" class="inline-block" aria-label="Inicio">
                    <span class="sr-only">Cineplanet</span>
                    <div hidden class="logo--single md:hidden block h-4 w-auto">
                        <Cineplanet />
                    </div>
                    <div class="logo--color h-9 w-auto">
                        <CineplanetColor />
                    </div>
                </a>
            </div>

            <nav
                class="hidden md:flex items-center gap-6 text-sm text-white"
                aria-label="Main navigation"
            >
                <a href="/peliculas" class="hover:underline">Películas</a>
                <a href="/cinemas" class="hover:underline">Cines</a>
                <a href="/promociones" class="hover:underline">Promociones</a>
                <a href="/socio-cineplanet" class="hover:underline">Socio</a>
                <a href="/dulceria-landing" class="hover:underline">Dulcería</a>
                <a href="/ventas-corporativas" class="hover:underline"
                    >Corporativo</a
                >
                <a
                    href="https://blog.cineplanet.com.pe/"
                    class="hover:underline"
                    target="_blank"
                    rel="noopener noreferrer">Blog</a
                >
            </nav>

            <div class="flex items-center gap-3">
                <button
                    class="md:hidden text-white p-2 rounded hover:bg-white/10"
                    onclick={() => (showMobileMenu = !showMobileMenu)}
                    aria-label="Abrir menú"
                >
                    <svg
                        class="h-6 w-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                </button>

                <div class="hidden md:flex items-center gap-4">
                    <div class="relative">
                        <button
                            class="text-white hover:underline"
                            onclick={toggleAccount}
                            aria-haspopup="true"
                            aria-expanded={showAccountFlyout}>Cuenta</button
                        >

                        {#if showAccountFlyout}
                            <div
                                class="absolute right-0 mt-2 w-48 bg-white text-gray-800 border rounded shadow-lg z-20 p-4"
                            >
                                {#if $user}
                                    <div class="mb-2">
                                        <span class="font-medium"
                                            >Hola, {getName($user)}!</span
                                        >
                                    </div>
                                    <i class="icon cineplanet-icon_search"></i>
                                    <button
                                        class="w-full text-left text-red-600 hover:underline"
                                        onclick={handleLogout}
                                        >Cerrar sesión</button
                                    >
                                {:else}
                                    <div class="flex flex-col space-y-2">
                                        <a
                                            href="/autenticacion/login"
                                            class="text-gray-800 hover:underline"
                                            >Iniciar Sesión</a
                                        >
                                        <a
                                            href="/autenticacion/registro"
                                            class="text-gray-800 hover:underline"
                                            >Registrarte</a
                                        >
                                    </div>
                                {/if}
                            </div>
                        {/if}
                    </div>

                    <a
                        href="/centro-de-ayuda"
                        class="text-white hover:underline">Ayuda</a
                    >
                    <button
                        aria-label="Buscar"
                        class="text-white hover:text-gray-300">🔍</button
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    {#if showMobileMenu}
        <div class="md:hidden bg-black/30 px-4 pb-4">
            <nav class="flex flex-col gap-2 text-white text-sm">
                <a class="block hover:underline py-2" href="/peliculas"
                    >Películas</a
                >
                <a class="block hover:underline py-2" href="/cinemas">Cines</a>
                <a class="block hover:underline py-2" href="/promociones"
                    >Promociones</a
                >
                <a class="block hover:underline py-2" href="/socio-cineplanet"
                    >Socio</a
                >
                <a class="block hover:underline py-2" href="/dulceria-landing"
                    >Dulcería</a
                >
                <a
                    class="block hover:underline py-2"
                    href="/ventas-corporativas">Corporativo</a
                >
                <a
                    class="block hover:underline py-2"
                    href="https://blog.cineplanet.com.pe/"
                    target="_blank"
                    rel="noopener noreferrer">Blog</a
                >
                <div class="pt-2 border-t border-white/10 mt-2">
                    {#if $user}
                        <div class="py-2">Hola, {getName($user)}!</div>
                        <button
                            class="block text-left w-full py-2 text-red-600 hover:underline"
                            onclick={handleLogout}>Cerrar sesión</button
                        >
                    {:else}
                        <a
                            href="/autenticacion/login"
                            class="block py-2 hover:underline">Iniciar Sesión</a
                        >
                        <a
                            href="/autenticacion/registro"
                            class="block py-2 hover:underline">Registrarte</a
                        >
                    {/if}
                </div>
            </nav>
        </div>
    {/if}
</header>
