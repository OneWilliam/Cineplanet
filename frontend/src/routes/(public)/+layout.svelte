<script>
    import "../../app.css";
    import logo from "$lib/assets/cineplanet.png";
    import Navbar from "$lib/components/Navbar.svelte";
    import Footer from "$lib/components/Footer.svelte";
    import { fetchUser } from "$lib/authStore.js";
    import { onMount } from "svelte";
    import "$lib/fonts/cineplanet-icon.css";
    export const ssr = false;

    onMount(() => {
        fetchUser();
    });

    let { children } = $props();
</script>

<svelte:head>
    <link rel="icon" href={logo} />
    <title>Cineplanet Perú I Lo mejor del cine y entretenimiento</title>
    <link
        href="https://fonts.googleapis.com/css?family=Lato:300,400,700,800,900|Montserrat:300,400,500,600,700,800,900|Inter:300,400,700,800,900"
        rel="stylesheet"
    />
</svelte:head>

<Navbar />

<main class="min-h-[60vh] bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {@render children()}
    </div>
</main>

<Footer />
