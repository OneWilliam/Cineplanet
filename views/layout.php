<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Cineplanet" ?></title>
    <link rel="icon" type="image/x-icon" href="/img/Cineplanet_logo_actual.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/base.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>
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
        // Header transparency effect for homepage
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = document.body.getAttribute('data-current-path');
            const header = document.querySelector('.header');

            if (currentPath === '/' || currentPath === '/index.php') {
                // Homepage: apply transparency effect on scroll
                function updateHeaderStyle() {
                    if (window.scrollY > 300) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                }
                
                window.addEventListener('scroll', updateHeaderStyle);
                updateHeaderStyle();
            }
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
