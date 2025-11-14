<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Cineplanet" ?></title>
    <link rel="icon" type="image/x-icon" href="/img/Cineplanet_logo_actual.svg">
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/base.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>
    <style id="dynamic-page-styles"></style>
    <style>
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .nav-account {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<?php
$currentPath = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
if ($currentPath === "/" || $currentPath === "/index.php") {
    $bodyClass = "inicio";
} else {
    $bodyClass = "otra";
}
?>
<body class="<?php echo $bodyClass; ?>" data-current-path="<?php echo htmlspecialchars(
    $currentPath,
); ?>">
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <img src="/img/logo-cineplanet.svg" alt="Cineplanet Logo" style="height: 40px; margin-right: 10px;">
            </a>
            <div class="nav-container">
                <nav class="nav-links">
                    <a href="/" class="nav-link"><i class="fas fa-home"></i> Inicio</a>
                    <a href="/peliculas" class="nav-link"><i class="fas fa-film"></i> Películas</a>
                    <a href="#" class="nav-link"><i class="fas fa-calendar-alt"></i> Cartelera</a>
                    <a href="#" class="nav-link"><i class="fas fa-candy-cane"></i> Dulcería</a>
                </nav>
                <nav class="nav-account">
                    <a href="/cuenta" class="nav-link nav-cuenta"><i class="fas fa-user"></i> Mi Cuenta</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="main" id="main">
        <?= $content ?? "" ?>
    </main>

    <script>
        // Escuchar eventos de htmx para manejar el CSS dinámico
        document.addEventListener('DOMContentLoaded', function() {
            // Función para cargar CSS dinámico
            function loadPageCSS(cssPath) {
                if (!cssPath) return;

                const styleId = 'dynamic-page-styles';
                const styleElement = document.getElementById(styleId);

                // Cargar el contenido del CSS y ponerlo en el elemento style
                fetch(cssPath)
                    .then(response => response.text())
                    .then(cssText => {
                        styleElement.innerHTML = cssText;
                    })
                    .catch(err => console.error('Error loading CSS:', err));
            }

            // Función para actualizar el estado del header
            function updateHeaderTransparency() {
                const currentPath = document.body.getAttribute('data-current-path');

                if (currentPath === '/' || currentPath === '/index.php') {
                    // Es la página de inicio, aplicar clase inicio
                    document.body.classList.add('inicio');
                    document.body.classList.remove('peliculas', 'otra');
                } else if (currentPath && currentPath.startsWith('/peliculas')) {
                    // Es una página de películas
                    document.body.classList.add('peliculas');
                    document.body.classList.remove('inicio', 'otra');
                } else {
                    // Otras páginas
                    document.body.classList.add('otra');
                    document.body.classList.remove('inicio', 'peliculas');
                }

                // Actualizar funcionalidad del header basado en la clase
                const header = document.querySelector('.header');

                if (document.body.classList.contains('inicio')) {
                    // En la página de inicio, aplicar efecto de transparencia
                    function updateHeaderStyle() {
                        if (window.scrollY > 300) { // Aproximadamente la altura del carrusel
                            header.classList.add('scrolled');
                        } else {
                            header.classList.remove('scrolled');
                        }
                    }

                    // Remover eventos anteriores si existen
                    window.removeEventListener('scroll', window.headerScrollHandler);
                    window.headerScrollHandler = updateHeaderStyle;
                    window.addEventListener('scroll', window.headerScrollHandler);
                    updateHeaderStyle(); // Inicializar
                } else {
                    // En otras páginas, asegurarse de que no tenga transparencia
                    header.classList.remove('scrolled'); // Remover la clase para forzar el estado blanco
                    window.removeEventListener('scroll', window.headerScrollHandler);
                }
            }

            // Escuchar el evento de htmx después de recibir la respuesta
            document.addEventListener('htmx:afterOnLoad', function(evt) {
                // Buscar si hay un script que indica qué CSS cargar
                const xhr = evt.detail.xhr;
                if (xhr.getResponseHeader('X-Page-CSS')) {
                    loadPageCSS(xhr.getResponseHeader('X-Page-CSS'));
                }

                // Actualizar la ruta actual si se proporciona en un encabezado
                const currentPath = xhr.getResponseHeader('X-Current-Path');
                if (currentPath) {
                    document.body.setAttribute('data-current-path', currentPath);
                    updateHeaderTransparency();
                }
            });

            // Cargar el CSS inicial si existe
            <?php if (isset($page_css)): ?>
            loadPageCSS('<?= $page_css ?>');
            <?php endif; ?>

            // Inicializar el estado del header
            updateHeaderTransparency();
        });
    </script>

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
                    <li><a href="#">Cartelera</a></li>
                    <li><a href="#">Dulcería</a></li>
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
</body>
</html>
