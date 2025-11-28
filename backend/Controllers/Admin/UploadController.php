<?php
namespace Cineplanet\App\Controllers\Admin;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UploadController
{
    public function uploadImage(Request $request, Response $response)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (empty($uploadedFiles["image"])) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "No se recibió ningún archivo.",
                ]),
            );
            return $response
                ->withStatus(400)
                ->withHeader("Content-Type", "application/json");
        }

        $uploadedFile = $uploadedFiles["image"];

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Error durante la subida del archivo.",
                ]),
            );
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", "application/json");
        }

        // --- Validación de Seguridad ---
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->buffer(
            $uploadedFile->getStream()->getContents(),
        );

        $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        if (!in_array($mimeType, $allowedTypes)) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" =>
                        "Tipo de archivo no permitido. Solo se aceptan imágenes (JPEG, PNG, GIF, WebP).",
                ]),
            );
            return $response
                ->withStatus(400)
                ->withHeader("Content-Type", "application/json");
        }

        // --- Mover el archivo ---
        $directory = PROJECT_ROOT . "/public/uploads";

        // VALIDACIÓN: Verificar si el directorio existe y tiene permisos de escritura.
        if (!is_dir($directory) || !is_writable($directory)) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" =>
                        "El directorio de destino para las subidas no existe o no tiene permisos de escritura.",
                    "path" => $directory,
                ]),
            );
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", "application/json");
        }

        $extension = pathinfo(
            $uploadedFile->getClientFilename(),
            PATHINFO_EXTENSION,
        );
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf("%s.%0.8s", $basename, $extension);

        $path = $directory . "/" . $filename;

        $uploadedFile->moveTo($path);

        $response->getBody()->write(
            json_encode([
                "success" => true,
                "message" => "Imagen subida correctamente.",
                "filePath" => "/uploads/" . $filename,
            ]),
        );

        return $response->withHeader("Content-Type", "application/json");
    }

    public function getUploadedImages(Request $request, Response $response)
    {
        $directory = PROJECT_ROOT . "/public/uploads";
        $imageFiles = [];

        if (is_dir($directory)) {
            // Escanear el directorio y filtrar los resultados no deseados
            $files = array_diff(scandir($directory), [".", ".."]);

            foreach ($files as $file) {
                // Asegurarse de que solo se listan archivos
                if (is_file($directory . "/" . $file)) {
                    $imageFiles[] = "/uploads/" . $file;
                }
            }
        }

        $response->getBody()->write(
            json_encode([
                "success" => true,
                "data" => $imageFiles,
            ]),
        );

        return $response->withHeader("Content-Type", "application/json");
    }
}
