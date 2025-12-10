<?php

namespace Cineplanet\App\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class AdminController
{
    /*
        CREATE TABLE admin (
            id_admin INT NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            FOREIGN KEY (id_admin) REFERENCES empleado(id_empleado),
            PRIMARY KEY (id_admin)
        )
    */
    private $pdo;

    /**
     * Constructor del controlador de admin para administración.
     * @param PDO $pdo Conexión PDO a la base de datos.
     * @see https://www.php.net/manual/es/class.pdo.php
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Obtener todos los admins.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAll(Request $request, Response $response)
    {
        $table = strtolower(str_replace('Controller', '', (new \ReflectionClass($this))->getShortName()));
        $candidates = ["vista" . $table, "vista_" . $table];
        $lastError = null;

        foreach ($candidates as $view) {
            try {
                $stmt = $this->pdo->query("SELECT * FROM `" . $view . "`");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (ob_get_level()) {
                    @ob_clean();
                }

                $response->getBody()->write(json_encode([
                    "success" => true,
                    "data" => $rows,
                ]));

                return $response->withHeader("Content-Type", "application/json");
            } catch (\PDOException $e) {
                // Guardar el último error para depuración y probar siguiente candidato
                $lastError = $e->getMessage();
            }
        }

        $response->getBody()->write(json_encode([
            "success" => false,
            "data" => [],
            "message" => $lastError ? ("DB error: " . $lastError) : ("No view found for table: " . $table),
        ]));

        return $response->withHeader("Content-Type", "application/json")->withStatus(500);
    }

    /**
     * Obtener los logs de administración.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getLogs(Request $request, Response $response){
        try {
            $stmt = $this->pdo->query("SELECT * FROM `adminlog` ORDER BY `hora` DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (ob_get_level()) {
                @ob_clean();
            }

            $response->getBody()->write(json_encode([
                "success" => true,
                "data" => $rows,
            ]));

            return $response->withStatus(200)->withHeader("Content-Type", "application/json");
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                "success" => false,
                "data" => [],
                "message" => "DB error: " . $e->getMessage(),
            ]));

            return $response->withHeader("Content-Type", "application/json")->withStatus(500);
        }
    }

    /**
     * Eliminar un admin por ID.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response)
    {
        try {
            $data = json_decode($request->getBody(), true);

            if (!$data) {
                $response->getBody()->write(json_encode([
                    "success" => false,
                    "message" => "No data provided",
                ]));
                return $response->withHeader("Content-Type", "application/json")->withStatus(400);
            }

            // Obtener el nombre de la tabla desde el nombre de la clase
            $table = strtolower(str_replace('Controller', '', (new \ReflectionClass($this))->getShortName()));

            $idKey = array_key_first($data);
            $idValue = $data[$idKey] ?? null;

            if (!$idKey || !$idValue) {
                $response->getBody()->write(json_encode([
                    "success" => false,
                    "message" => "ID field not provided",
                ]));
                return $response->withHeader("Content-Type", "application/json")->withStatus(400);
            }

            $stmt = $this->pdo->prepare("DELETE FROM `" . $table . "` WHERE `" . $idKey . "` = :id");
            $stmt->bindParam(':id', $idValue);
            $stmt->execute();

            $response->getBody()->write(json_encode([
                "success" => true,
                "message" => "Record deleted successfully",
            ]));

            return $response->withHeader("Content-Type", "application/json")->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                "success" => false,
                "message" => "DB error: " . $e->getMessage(),
            ]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(500);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                "success" => false,
                "message" => "Error: " . $e->getMessage(),
            ]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(500);
        }
    }
}
