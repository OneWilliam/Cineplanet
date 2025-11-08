<h2>Listado de Películas</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Duración</th>
            <th>Género</th>
            <th>Clasificación</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($peliculas as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p["nombre"]) ?></td>
            <td><?= htmlspecialchars($p["duracion"]) ?> min</td>
            <td><?= htmlspecialchars($p["genero"]) ?></td>
            <td><?= htmlspecialchars($p["clasificacion"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
