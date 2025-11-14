<div class="admin-page">
    <h1>Gestión de Cines</h1>

    <div class="admin-actions">
        <a href="/admin/cines/nuevo" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cine
        </a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ciudad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cines)): ?>
                    <?php foreach ($cines as $cine): ?>
                    <tr>
                        <td><?= htmlspecialchars($cine['id_cine'] ?? '') ?></td>
                        <td><?= htmlspecialchars($cine['nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($cine['ciudad'] ?? '') ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-secondary">Editar</a>
                            <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay cines registrados</td>
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
    
    .admin-actions {
        margin-bottom: 20px;
    }
    
    .btn {
        display: inline-block;
        padding: 8px 16px;
        margin: 0 5px;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }
    
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
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
</style>