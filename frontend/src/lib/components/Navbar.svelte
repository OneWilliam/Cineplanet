<script>
    import { onMount, onDestroy } from "svelte";
    import Cineplanet from "./Icons/Cineplanet.svelte";
    import CineplanetColor from "./Icons/CineplanetColor.svelte";
    import { user, logout } from "$lib/authStore.js";
    import { page } from "$app/state";

    let p = $derived(page.url.pathname);
    let isMovie = $derived(/\/peliculas\/.+/.test(p));
    let isHome = $derived(p === "/" || isMovie);

    let isTransparentHeader = $state(true);
    $effect(() => {
        if (!isHome && !isMovie) {
            isTransparentHeader = false;
        } else {
            if (isMovie) {
                isTransparentHeader = scrollY < 360;
            } else {
                isTransparentHeader = scrollY < 650;
            }
        }
    });
    let scrollY = $state(0);

    let showAccountFlyout = $state(false);
    let showMobileMenu = $state(false);
    // Castear el tipo a any
    let currentUser = $derived(() => /** @type {any} */ $user);

    onMount(() => {
        scrollY = window.scrollY;

        function handleScroll() {
            scrollY = window.scrollY;
            if (isMovie) {
                isTransparentHeader = scrollY < 360;
            } else if (isHome) {
                isTransparentHeader = scrollY < 650;
            }
        }

        window.addEventListener("scroll", handleScroll);

        return () => window.removeEventListener("scroll", handleScroll);
    });

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
    class={`fixed top-0 left-0 w-full z-50  transition-colors border-b   border-white  duration-200 ${isTransparentHeader ? "bg-linear-to-b  from-black/50 to-transparent shadow-md text-white" : "bg-white text-gray-800 shadow-md"}`}
>
    <div class="max-w-[1070px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-4">
                <a href="/" class="inline-block" aria-label="Inicio">
                    <span class="sr-only">Cineplanet</span>
                    {#if isTransparentHeader}
                        <div class="logo--color hidden md:block h-9 w-auto">
                            <Cineplanet />
                        </div>
                    {:else}
                        <div class="logo--color hidden md:block h-9 w-auto">
                            <CineplanetColor />
                        </div>
                    {/if}
                </a>
            </div>

            <nav
                class={`hidden md:flex items-center gap-6  ${isTransparentHeader ? "text-white" : "text-gray-800"}`}
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

            <div class="flex items-center gap-4">
                <button
                    class={`md:hidden p-2  rounded ${isTransparentHeader ? "text-white hover:bg-white/10" : "text-gray-800 hover:bg-gray-100"}`}
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

                <div
                    class={`hidden md:flex items-center gap-3  ${isTransparentHeader ? "text-white" : "text-gray-800"}`}
                >
                    <div class="relative">
                        <button
                            class={`flex  items-center gap-2 ${isTransparentHeader ? "text-white hover:text-white/80" : "text-gray-800 hover:text-gray-600"}`}
                            aria-haspopup="true"
                            aria-label="Cuenta"
                        >
                            <i
                                class="icon cineplanet-icon_login text-[28px]"
                                onclick={toggleAccount}
                                aria-hidden="true"
                            ></i>
                        </button>

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
                    <span
                        aria-label="Buscar"
                        class={` ${isTransparentHeader ? "text-white" : "text-gray-800"} hover:text-gray-600`}
                    >
                        <i
                            class="icon cineplanet-icon_search text-[28px]"
                            onclick={() => alert("MIssingggggggg")}
                            aria-hidden="true"
                        ></i>
                    </span>
                    <a
                        href="/centro-de-ayuda"
                        class={`flex items-center ${isTransparentHeader ? "text-white" : "text-gray-800"} `}
                        aria-label="Ayuda"
                    >
                        <i
                            class="icon cineplanet-icon_help text-[28px]"
                            aria-hidden="true"
                        ></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {#if showMobileMenu}
        <div class="md:hidden bg-black/30 px-16 pb-14">
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
