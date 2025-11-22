<?php

namespace Cineplanet\App\Service;

class ImageService
{
    public function getMovieImages(array $peliculas): array
    {
        $assetsDir = realpath(__DIR__ . "/../../public/assets/peliculas");
        $folderMap = [];

        if ($assetsDir && is_dir($assetsDir)) {
            $dirs = glob($assetsDir . "/*", GLOB_ONLYDIR);
            foreach ($dirs as $d) {
                $key = basename($d); // nombre de la carpeta (slug)
                $images = [];
                foreach (
                    glob($d . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE)
                    as $imgPath
                ) {
                    $images[] =
                        "/assets/peliculas/" . $key . "/" . basename($imgPath);
                }
                if (!empty($images)) {
                    $folderMap[$key] = $images;
                }
            }
        }

        $slugify = function ($text) {
            $text = mb_strtolower(trim($text), "UTF-8");
            $trans = @iconv("UTF-8", "ASCII//TRANSLIT", $text);
            if ($trans !== false) {
                $text = $trans;
            }
            $text = preg_replace("/[^a-z0-9]+/i", "-", $text);
            $text = trim($text, "-");
            return $text;
        };

        // Para cada película, adjuntar un array 'images' con rutas públicas encontradas (puede estar vacío)
        foreach ($peliculas as &$p) {
            $nombre = $p["nombre"] ?? "";
            $slug = $slugify($nombre);

            if (isset($folderMap[$slug])) {
                // Si existe una carpeta con el slug, usar todas las imágenes dentro
                $p["images"] = $folderMap[$slug];
            } else {
                // Si no hay carpeta, intentar encontrar archivos sueltos en /public/assets basados en slug/nombre
                $this->findImagesForMovie($p, $slug, $nombre);
            }
        }
        unset($p);

        return $peliculas;
    }

    private function findImagesForMovie(array &$pelicula, string $slug, string $nombre): void
    {
        $candidates = [
            "/assets/" . $slug . ".jpg",
            "/assets/" . $slug . ".png",
            "/assets/" . $slug . ".jpeg",
            "/assets/" . strtolower($nombre) . ".jpg",
            "/assets/" .
            str_replace(" ", "_", strtolower($nombre)) .
            ".jpg",
        ];
        $found = [];
        foreach ($candidates as $c) {
            $fs = realpath(__DIR__ . "/../../public" . $c);
            if ($fs && file_exists($fs)) {
                $found[] = $c;
            }
        }
        $pelicula["images"] = $found; // puede quedar vacío -> la vista usará fallback si es necesario
    }

    public function resolveAssetForMovie(string $nombre): ?string
    {
        $assetWebBase = "/assets/";
        $assetsFsDir = realpath(__DIR__ . "/../../public/assets");

        if ($assetsFsDir === false) {
            $assetsFsDir = __DIR__ . "/../../public/assets";
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

        // Helper function to slugify
        $slugify = function ($text) {
            $text = mb_strtolower($text, "UTF-8");
            $trans = @iconv("UTF-8", "ASCII//TRANSLIT", $text);
            if ($trans !== false) {
                $text = $trans;
            }
            $text = preg_replace("/[^a-z0-9]+/i", "_", $text);
            $text = trim($text, "_");
            return $text;
        };

        // Variantes a partir del slug
        $slug = $slugify($nameTrim);
        if (!empty($slug)) {
            $candidates[] = $slug . ".jpg";
            $candidates[] = $slug . ".png";
            $candidates[] = $slug . ".jpeg";
            $candidates[] = $slug . ".webp";
        }

        // Variantes con espacios y minúsculas
        $candidates[] = strtolower($nameTrim) . ".jpg";
        $candidates[] = strtolower($nameTrim) . ".png";

        // También probar cambiando espacios por guiones
        $candidates[] = str_replace(" ", "-", strtolower($nameTrim)) . ".jpg";
        $candidates[] = str_replace(" ", "-", strtolower($nameTrim)) . ".png";

        // Recorrer candidatos y devolver el primero que exista
        foreach ($candidates as $file) {
            $fsPath = $assetsFsDir . DIRECTORY_SEPARATOR . $file;
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
            $fsPath = $assetsFsDir . DIRECTORY_SEPARATOR . $fb;
            if (file_exists($fsPath)) {
                return $assetWebBase . $fb;
            }
        }

        // Último recurso: ruta a fallback genérica
        return $assetWebBase . "default.png";
    }
}