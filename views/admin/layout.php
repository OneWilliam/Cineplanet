<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin - Cineplanet') ?></title>
    <link rel="icon" type="image/x-icon" href="/img/Cineplanet_logo_actual.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/admin.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>
    <style id="dynamic-page-styles"></style>
</head>
<body class="admin-body">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h3><i class="fas fa-cinema"></i> Admin Cineplanet</h3>
            </div>
            
            <nav class="admin-nav">
                <a href="/admin" class="nav-item <?= $current_section === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="/admin/peliculas" class="nav-item <?= $current_section === 'peliculas' ? 'active' : '' ?>">
                    <i class="fas fa-film"></i> Películas
                </a>
                <a href="/admin/cines" class="nav-item <?= $current_section === 'cines' ? 'active' : '' ?>">
                    <i class="fas fa-theater-masks"></i> Cines
                </a>
                <a href="/admin/usuarios" class="nav-item <?= $current_section === 'usuarios' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Usuarios
                </a>
                <a href="/admin/funciones" class="nav-item <?= $current_section === 'funciones' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> Funciones
                </a>
                <a href="/admin/reportes" class="nav-item <?= $current_section === 'reportes' ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
            </nav>
            
            <div class="admin-user">
                <p>Bienvenido, <?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Admin'); ?></p>
                <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </aside>
        
        <main class="admin-main">
            <?= $content ?? "" ?>
        </main>
    </div>
    
    <script>
        // Simple script to handle active state of navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.admin-nav .nav-item');
            
            navItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>