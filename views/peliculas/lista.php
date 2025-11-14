<h2 class="text-white">Cartelera de Películas</h2>

<div class="peliculas-grid">
    <?php if (!empty($peliculas)): ?>
        <?php foreach ($peliculas as $pelicula): ?>
            <div class="pelicula-card">
                <h3 class="pelicula-title"><?= htmlspecialchars($pelicula['nombre'] ?? 'Título desconocido') ?></h3>
                <p class="pelicula-info">Duración: <?= htmlspecialchars($pelicula['duracion'] ?? 'N/A') ?> min</p>
                <a href="/peliculas/<?= $pelicula['pelicula_id'] ?? '#' ?>" class="btn-comprar">Comprar Entrada</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-white">No hay películas disponibles en este momento.</p>
    <?php endif; ?>
</div>