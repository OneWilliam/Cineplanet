<?php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cineplanet\App\View;
use Cineplanet\App\Database;

class InicioController
{
    public function index(Request $request, Response $response, $args)
    {
        try {
            $db = Database::getConnection();

            // Obtener las películas desde la base de datos usando el procedure listado
            $stmt = $db->prepare("CALL listarPeliculas()");
            $stmt->execute();
            $peliculas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // En caso de error devolvemos un arreglo vacío para no romper la vista
            $peliculas = [];
        }

        // Regla: cada subcarpeta en public/assets/peliculas representa una película (slug)
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

        // helper slugify (coincide con la convención de nombres de carpeta)
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
                $p["images"] = $found; // puede quedar vacío -> la vista usará fallback si es necesario
            }
        }
        unset($p);

        return View::render($response, "inicio", [
            "titulo" => "Inicio - Cineplanet",
            "peliculas" => $peliculas,
            "page_css" => "/css/inicio.css",
        ]);
    }

    public function peliculas(Request $request, Response $response, $args)
    {
        try {
            $db = Database::getConnection();

            $stmt = $db->prepare("CALL listarPeliculas()");
            $stmt->execute();
            $peliculas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $hxRequest = $request->getHeaderLine("HX-Request");
            if ($hxRequest === "true") {
                return View::renderPartial($response, "peliculas/lista", [
                    "peliculas" => $peliculas,
                    "page_css" => "/css/peliculas.css",
                ]);
            } else {
                return View::render($response, "peliculas/lista", [
                    "peliculas" => $peliculas,
                    "page_css" => "/css/peliculas.css",
                ]);
            }
        } catch (\Exception $e) {
            $html =
                '<div class="alert alert-danger">Error: ' .
                htmlspecialchars($e->getMessage()) .
                "</div>";
            $response->getBody()->write($html);
            return $response->withStatus(500);
        }
    }
}
