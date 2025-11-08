<h2>Listado de Películas</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Duración</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($peliculas as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p["nombre"]) ?></td>
            <td><?= htmlspecialchars($p["duracion"]) ?> min</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
