<div class="admin-dashboard">
    <h1>Panel de Administración</h1>
    
    <div class="admin-stats">
        <div class="stat-card">
            <i class="fas fa-film"></i>
            <h3><?= $total_peliculas ?? 0 ?></h3>
            <p>Películas</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-theater-masks"></i>
            <h3><?= $total_cines ?? 0 ?></h3>
            <p>Cines</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?= $total_usuarios ?? 0 ?></h3>
            <p>Usuarios</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-calendar-alt"></i>
            <h3><?= $total_funciones ?? 0 ?></h3>
            <p>Funciones</p>
        </div>
    </div>
    
    <div class="admin-quick-actions">
        <h2>Acciones Rápidas</h2>
        <div class="actions-grid">
            <a href="/admin/peliculas/nueva" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Agregar Película</span>
            </a>
            <a href="/admin/cines/nuevo" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Agregar Cine</span>
            </a>
            <a href="/admin/usuarios" class="action-btn">
                <i class="fas fa-users"></i>
                <span>Administrar Usuarios</span>
            </a>
            <a href="/admin/funciones/nueva" class="action-btn">
                <i class="fas fa-calendar-plus"></i>
                <span>Agregar Función</span>
            </a>
        </div>
    </div>
    
    <div class="recent-section">
        <h2>Actividad Reciente</h2>
        <div class="recent-activity">
            <p>Últimas actualizaciones y cambios en el sistema...</p>
        </div>
    </div>
</div>

<style>
    .admin-dashboard {
        padding: 20px;
    }
    
    .admin-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .stat-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stat-card i {
        font-size: 2em;
        color: #007bff;
        margin-bottom: 10px;
    }
    
    .stat-card h3 {
        font-size: 1.8em;
        margin: 10px 0;
        color: #333;
    }
    
    .admin-quick-actions {
        margin: 30px 0;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        background: #007bff;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .action-btn i {
        font-size: 2em;
        margin-bottom: 10px;
    }
    
    .recent-section {
        margin-top: 30px;
    }
    
    .recent-activity {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }
</style>