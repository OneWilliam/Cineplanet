<!DOCTYPE html>
<html lang="es" x-data="{ currentView: '<?php echo basename($_SERVER['SCRIPT_NAME'], '.php') ?: (explode('/', trim($_SERVER['REQUEST_URI'], '/'))[0] ?? 'inicio'); ?>' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Cineplanet" ?></title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? uniqid(); ?>">

    <!-- HTMX & Alpine.js -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Main Styles -->
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/components.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>

    <!-- Alpine Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                title: '<?= addslashes($title ?? "Cineplanet") ?>',
                user: Alpine.reactive({
                    isLoggedIn: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>,
                    name: '<?php echo addslashes($_SESSION['user_nombre'] ?? ''); ?>',
                    role: '<?php echo addslashes($_SESSION['user_rol'] ?? ''); ?>'
                }),
                favorites: Alpine.reactive([]),
                cart: Alpine.reactive({
                    items: [],
                    total: 0
                })
            });
        });
    </script>

    <style>
        /* HTMX indicators */
        .htmx-indicator {
            display: none;
        }
        .htmx-request .htmx-indicator {
            display: inline-block;
        }
        .htmx-request {
            opacity: 0.6;
        }
    </style>
</head>
<body class="htmx-indicator">
    <!-- Header with HTMX navigation -->
    <header class="header" x-data="{ isOpen: false }">
        <div class="header-content">
            <div class="logo-container">
                <a href="/" class="logo">
                    <img src="/img/logo-cineplanet.svg" alt="Cineplanet Logo" class="logo-img">
                </a>
            </div>

            <nav class="nav-menu" :class="{ 'nav-open': isOpen }" x-show="!isOpen">
                <a href="/" class="nav-link"
                   :class="{ 'active': $store.app.currentView === 'inicio' }"
                   hx-get="/"
                   hx-target="#main-content"
                   hx-push-url="true"
                   @click="$store.app.currentView = 'inicio'">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="/peliculas" class="nav-link"
                   :class="{ 'active': $store.app.currentView === 'peliculas' }"
                   hx-get="/peliculas"
                   hx-target="#main-content"
                   hx-push-url="true"
                   @click="$store.app.currentView = 'peliculas'">
                    <i class="fas fa-film"></i> Películas
                </a>
                <a href="/cartelera" class="nav-link"
                   hx-get="/peliculas"
                   hx-target="#main-content"
                   hx-push-url="true">
                    <i class="fas fa-calendar-alt"></i> Cartelera
                </a>
                <a href="/dulceria" class="nav-link"
                   hx-get="/peliculas"
                   hx-target="#main-content"
                   hx-push-url="true">
                    <i class="fas fa-candy-cane"></i> Dulcería
                </a>
            </nav>

            <div class="nav-account">
                <template x-if="$store.app.user.isLoggedIn">
                    <div class="user-menu" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="user-btn">
                            <i class="fas fa-user"></i>
                            <span x-text="$store.app.user.name"></span>
                            <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-menu" x-show="dropdownOpen" @click.outside="dropdownOpen = false">
                            <a href="/cuenta" class="dropdown-item">
                                <i class="fas fa-user-circle"></i> Mi Cuenta
                            </a>
                            <template x-if="$store.app.user.role === 'admin'">
                                <a href="/admin" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Admin
                                </a>
                            </template>
                            <button hx-post="/logout"
                                    hx-headers='{"X-Requested-With": "htmx"}'
                                    class="dropdown-item"
                                    hx-trigger="click">
                                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="!$store.app.user.isLoggedIn">
                    <div class="auth-buttons">
                        <a href="/login" class="btn btn-outline">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                        <a href="/register" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </a>
                    </div>
                </template>
            </div>

            <button class="nav-toggle" @click="isOpen = !isOpen">
                <i class="fas fa-bars" x-show="!isOpen"></i>
                <i class="fas fa-times" x-show="isOpen"></i>
            </button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main id="main-content" class="main-content">
        <?= $content ?? "" ?>
    </main>

    <!-- Global Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Acerca de Cineplanet</h3>
                <p>La mejor experiencia de cine en Perú</p>
            </div>
            <div class="footer-section">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/peliculas">Películas</a></li>
                    <li><a href="/cartelera">Cartelera</a></li>
                    <li><a href="/dulceria">Dulcería</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contacto</h4>
                <p>Atención al cliente: 01 411-1111</p>
                <p>info@cineplanet.com.pe</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Cineplanet. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Global Alpine/HTMX Scripts -->
    <script>
        // HTMX configuration
        document.body.addEventListener('htmx:configRequest', function(evt) {
            evt.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content || '';
        });

        // Handle HTMX response errors
        document.body.addEventListener('htmx:responseError', function(evt) {
            console.error('HTMX Response Error:', evt.detail.xhr);
            alert('Error al cargar el contenido. Por favor, inténtelo de nuevo.');
        });

        // Update Alpine store when user data changes
        document.body.addEventListener('htmx:afterOnLoad', function(evt) {
            if (evt.detail.successful) {
                // Update navigation UI based on current page
                updateNavigation();
            }
        });

        function updateNavigation() {
            const path = window.location.pathname;
            const view = path.substring(1).split('/')[0] || 'inicio';

            if (Alpine.store) {
                Alpine.store('app').currentView = view;
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', updateNavigation);
    </script>
</body>
</html>
