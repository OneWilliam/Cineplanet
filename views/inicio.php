<?php
// Renderiza un carrusel usando los registros de $peliculas proporcionados por el controlador.
$peliculas = $peliculas ?? [];

// Buscar automáticamente una imagen en /public/assets
function slugify_simple(string $text): string
{
    $text = mb_strtolower($text, "UTF-8");
    // Intentar transliterar acentos a ascii
    $trans = @iconv("UTF-8", "ASCII//TRANSLIT", $text);
    if ($trans !== false) {
        $text = $trans;
    }
    // reemplazar cualquier caracter no alfanumérico por guion bajo
    $text = preg_replace("/[^a-z0-9]+/i", "_", $text);
    $text = trim($text, "_");
    return $text;
}

function resolve_asset_for_pelicula(string $nombre): ?string
{
    $assetWebBase = "/assets/";
    // Ruta al directorio public/assets desde esta vista
    $assetsFsDir = realpath(__DIR__ . "/../public/assets");
    if ($assetsFsDir === false) {
        // Fallback si realpath falla: construir ruta relativa
        $assetsFsDir = __DIR__ . "/../public/assets";
    }

    $candidates = [];

    $nameTrim = trim($nombre);
    $lower = mb_strtolower($nameTrim, "UTF-8");

    // Mapa manual para casos donde el archivo no coincide exactamente con el nombre
    $manualMap = [
        "interestelar" => "interestelar.png",
        "moonlighten" => "moonlighten.jpg",
        "the queen" => "the quen.jpg",
        "the pianist" => "the pianist.jpg",
    ];

    if (isset($manualMap[$lower])) {
        $candidates[] = $manualMap[$lower];
    }

    // Variantes a partir del slug
    $slug = slugify_simple($nameTrim);
    if (!empty($slug)) {
        $candidates[] = $slug . ".jpg";
        $candidates[] = $slug . ".png";
        $candidates[] = $slug . ".jpeg";
        $candidates[] = $slug . ".webp";
    }

    // Variantes con espacios y minúsculas tal- cual (por si los archivos usan espacios)
    $candidates[] = strtolower($nameTrim) . ".jpg";
    $candidates[] = strtolower($nameTrim) . ".png";

    // También probar cambiando espacios por guiones
    $candidates[] = str_replace(" ", "-", strtolower($nameTrim)) . ".jpg";
    $candidates[] = str_replace(" ", "-", strtolower($nameTrim)) . ".png";

    // Recorrer candidatos y devolver el primero que exista
    foreach ($candidates as $file) {
        $fsPath =
            rtrim($assetsFsDir, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            $file;
        if (file_exists($fsPath)) {
            return $assetWebBase . $file;
        }
    }

    $fallbacksKnown = [
        "interestelar.png",
        "moonlighten.jpg",
        "the pianist.jpg",
        "the quen.jpg",
    ];
    foreach ($fallbacksKnown as $fb) {
        $fsPath =
            rtrim($assetsFsDir, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            $fb;
        if (file_exists($fsPath)) {
            return $assetWebBase . $fb;
        }
    }

    // Último recurso: ruta a fallback genérica (puede no existir; ideal crear /public/assets/default.jpg)
    return $assetWebBase . "default.png";
}
?>

<div class="inicio-content">
    <div class="hero-carousel">
        <?php if (empty($peliculas)): ?>
            <!-- Carrusel por defecto si no hay películas en BD -->
            <div class="carousel-slide active" style="background-image: url('/assets/paw_patrol.jpg');">
                <div class="carousel-content">
                    <h2>Paw Patrol Especial de navidad</h2>
                    <p>¡Los cachorros de PAW Patrol llegan para rescatar la Navidad! Súmate a la diversión en la pantalla grande.</p>
                    <a href="/peliculas" class="btn btn-carousel">
                        <i class="fas fa-ticket-alt"></i> COMPRAR
                    </a>
                </div>
            </div>

            <div class="carousel-slide" style="background-image: url('/assets/monsta_x.jpg');">
                <div class="carousel-content">
                    <h2>MONSTA X : CONNECT X IN CINEMAS</h2>
                    <p>En julio de 2025, MONSTA X incendió el KSPO DOME durante tres noches inolvidables con su impactante espectáculo.</p>
                    <a href="/peliculas" class="btn btn-carousel">
                        <i class="fas fa-ticket-alt"></i> PREVENTA
                    </a>
                </div>
            </div>

            <div class="carousel-slide" style="background-image: url('/assets/correcaminos.jpg');">
                <div class="carousel-content">
                    <h2>El Correcaminos</h2>
                    <p>Una divertida aventura familiar en la pantalla grande.</p>
                    <a href="/peliculas" class="btn btn-carousel">
                        <i class="fas fa-ticket-alt"></i> COMPRAR
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($peliculas as $i => $pel):

                $nombre = $pel["nombre"] ?? "Película";
                $duracion = isset($pel["duracion"])
                    ? (int) $pel["duracion"]
                    : null;
                $img = resolve_asset_for_pelicula($nombre);
                $active = $i === 0 ? " active" : "";
                ?>
                <div class="carousel-slide<?php echo $active; ?>" style="background-image: url('<?php echo htmlspecialchars(
    $img,
); ?>');">
                    <div class="carousel-content">
                        <h2><?php echo htmlspecialchars($nombre); ?></h2>
                        <?php if ($duracion): ?>
                            <p>Duración: <?php echo $duracion; ?> min</p>
                        <?php else: ?>
                            <p>Duración: próximamente</p>
                        <?php endif; ?>
                        <a href="/peliculas" class="btn btn-carousel">
                            <i class="fas fa-ticket-alt"></i> VER
                        </a>
                    </div>
                </div>
            <?php
            endforeach; ?>
        <?php endif; ?>
    </div>

    <section class="peliculas-section" aria-labelledby="peliculas-title">
        <h2 id="peliculas-title" class="peliculas-title">Películas</h2>

        <nav class="peliculas-tabs" role="tablist" aria-label="Filtros de películas">
            <a href="#en-cartelera" class="active" role="tab">En cartelera</a>
            <a href="#preventa" role="tab">Preventa</a>
            <a href="#proximos" role="tab">Próximos estrenos</a>
        </nav>

        <div class="peliculas-grid" role="list">
            <?php if (!empty($peliculas) && is_array($peliculas)): ?>
                <?php foreach ($peliculas as $pel):

                    // Determinar imagen principal: preferir images[0] o image (si fueron asignadas por el controlador)
                    $img = "/assets/default.jpg";
                    if (!empty($pel["image"])) {
                        $img = $pel["image"];
                    } elseif (
                        !empty($pel["images"]) &&
                        is_array($pel["images"]) &&
                        count($pel["images"]) > 0
                    ) {
                        $img = $pel["images"][0];
                    } else {
                        // intentar resolver por slug-nombre en assets root si existe
                        $slug = strtolower(
                            trim(
                                preg_replace(
                                    "/[^a-z0-9]+/i",
                                    "_",
                                    $pel["nombre"] ?? "",
                                ),
                            ),
                        );
                        $candidate = "/assets/" . $slug . ".jpg";
                        if (file_exists(__DIR__ . "/../public" . $candidate)) {
                            $img = $candidate;
                        }
                    }
                    $title = htmlspecialchars(
                        $pel["nombre"] ?? "Título desconocido",
                    );
                    $duration = !empty($pel["duracion"])
                        ? (int) $pel["duracion"]
                        : null;
                    ?>
                    <article class="pelicula-card" role="listitem" aria-label="<?php echo $title; ?>">
                        <?php if (
                            !empty($pel["images"]) &&
                            count($pel["images"]) > 1
                        ): ?>
                            <!-- Si hay varias imágenes, mostrar la primera y permitir que JS cambie si se desea -->
                            <img src="<?php echo htmlspecialchars(
                                $img,
                            ); ?>" alt="<?php echo $title; ?> poster">
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars(
                                $img,
                            ); ?>" alt="<?php echo $title; ?> poster">
                        <?php endif; ?>

                        <div style="padding: 0.6rem 0;">
                            <h3 class="pelicula-title"><?php echo $title; ?></h3>
                            <?php if ($duration): ?>
                                <p class="pelicula-info">Duración: <?php echo $duration; ?> min</p>
                            <?php else: ?>
                                <p class="pelicula-info">Duración: N/A</p>
                            <?php endif; ?>
                        </div>

                        <a class="btn-comprar" href="/peliculas">
                            Ver más
                        </a>
                    </article>
                <?php
                endforeach; ?>
            <?php else: ?>
                <!-- Fallback si no hay películas -->
                <div class="pelicula-card">
                    <img src="/assets/paw_patrol.jpg" alt="Destacado">
                    <h3 class="pelicula-title">Próximamente</h3>
                    <p class="pelicula-info">No hay películas cargadas en la base de datos.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
(function () {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    if (!slides || slides.length === 0) return;
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    if (totalSlides > 1) {
        setInterval(nextSlide, 5000);
    }
})();
</script>
