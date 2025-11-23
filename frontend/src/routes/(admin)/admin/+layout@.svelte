<script>
    import "../../../app.css";
    let { children } = $props();
    export const ssr = false;
</script>

<div>Dasboard de Admin</div>
{@render children()}
