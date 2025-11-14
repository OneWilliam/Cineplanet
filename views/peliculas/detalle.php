<div class="pelicula-detalle">
    <h2><?= htmlspecialchars($pelicula['nombre'] ?? 'Película no encontrada') ?></h2>

    <div class="pelicula-info">
        <p><strong>Duración:</strong> <?= htmlspecialchars($pelicula['duracion'] ?? 'N/A') ?> minutos</p>
        <!-- Aquí puedes mostrar más detalles de la película -->
    </div>

    <div class="acciones-pelicula">
        <button class="btn-comprar" onclick="alert('Funcionalidad de compra de entradas en desarrollo')">Comprar Entrada</button>
        <a href="/peliculas" class="btn-volver">← Volver al Catálogo</a>
    </div>
</div>