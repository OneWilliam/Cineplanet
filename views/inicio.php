<h1>Bienvenido a Cineplanet</h1>
<p>¡Disfruta de la mejor cartelera de películas!</p>
<?php if ($dbStatus === "success"): ?>
    <div class="alert alert-success">Conexión a la base de datos exitosa.</div>
<?php else: ?>
    <div class="alert alert-danger">Error de conexión: <?= htmlspecialchars(
        $dbError,
    ) ?></div>
<?php endif; ?>
<p>
    <a href="/peliculas" hx-get="/peliculas" hx-target="#main" hx-swap="innerHTML" class="btn btn-primary">Ver películas</a>
</p>
