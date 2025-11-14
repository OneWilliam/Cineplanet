<div class="admin-page">
    <h1>Gestión de Usuarios</h1>
    
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id_usuario'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['nombre'] ?? '') ?> <?= htmlspecialchars($usuario['apellido'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                        <td>
                            <span class="role-badge role-<?= htmlspecialchars($usuario['rol_nombre'] ?? '') ?>">
                                <?= htmlspecialchars($usuario['rol_nombre'] ?? '') ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= htmlspecialchars($usuario['estado'] ?? '') ?>">
                                <?= htmlspecialchars($usuario['estado'] ?? '') ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($usuario['fecha_registro'] ?? '') ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-secondary">Editar</a>
                            <?php if ($usuario['rol_nombre'] !== 'admin'): ?>
                                <?php if ($usuario['estado'] === 'activo'): ?>
                                    <a href="#" class="btn btn-sm btn-warning">Desactivar</a>
                                <?php else: ?>
                                    <a href="#" class="btn btn-sm btn-success">Activar</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay usuarios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .admin-page {
        padding: 20px;
    }
    
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin: 0 2px;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 12px;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-warning {
        background-color: #ffc107;
        color: black;
    }
    
    .btn-success {
        background-color: #28a745;
        color: white;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 11px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .admin-table th,
    .admin-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .admin-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    .role-badge, .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .role-admin {
        background-color: #dc3545;
        color: white;
    }
    
    .role-cliente {
        background-color: #17a2b8;
        color: white;
    }
    
    .status-activo {
        background-color: #28a745;
        color: white;
    }
    
    .status-inactivo {
        background-color: #6c757d;
        color: white;
    }
    
    .status-bloqueado {
        background-color: #dc3545;
        color: white;
    }
</style>