<div class="error-container">
    <h2><?= htmlspecialchars($titulo ?? 'Error') ?></h2>
    <div class="error-message">
        <p><?= htmlspecialchars($mensaje ?? 'Ocurrió un error inesperado.') ?></p>
    </div>
    <a href="/" class="btn-volver">← Volver al Inicio</a>
</div>

<style>
    .error-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        text-align: center;
        padding: 20px;
    }
    .error-message {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 20px;
        border-radius: 5px;
        margin: 20px 0;
        max-width: 600px;
    }
    .btn-volver {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 15px;
    }
    .btn-volver:hover {
        background-color: #0056b3;
    }
</style>