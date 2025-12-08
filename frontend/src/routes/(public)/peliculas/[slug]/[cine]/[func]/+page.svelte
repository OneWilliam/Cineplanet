<script lang="ts">
    import type { PageProps } from "./$types";

    let { data }: PageProps = $props();
</script>

<h1>Cine: {data.cine}</h1>
<h1>Funcion: {data.func}</h1>
