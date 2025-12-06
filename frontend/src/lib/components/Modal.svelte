<script>
    export let show = false;

    export let onClose = () => {};

    export let title = "";

    function close() {
        onClose();
    }
</script>

{#if show}
    <div class="modal-backdrop" on:click={close}></div>
    <div class="modal">
        <div class="modal-header">
            <h3>{title}</h3>
            <button on:click={close}>&times;</button>
        </div>
        <div class="modal-body">
            <slot></slot>
        </div>
    </div>
{/if}

<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
    }
    .modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        z-index: 1001;
        width: 80%;
        max-width: 500px;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .modal-header button {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }
    .modal-body {
        padding-top: 10px;
    }
</style>
