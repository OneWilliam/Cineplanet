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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet"
    />
</svelte:head>

<Navbar />

<main class="min-h-[60vh] bg-white">
    {@render children()}
</main>

<Footer />
