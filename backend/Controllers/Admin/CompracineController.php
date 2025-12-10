<?php

namespace Cineplanet\App\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class CompracineController
{
    /*
        CREATE TABLE compracine (
            id_sala INT NOT NULL,
            id_pelicula INT NOT NULL,
            id_horario INT NOT NULL,
            fila INT NOT NULL,
            columna INT NOT NULL,
            id_cineticket INT NOT NULL,
            PRIMARY KEY (id_sala, id_pelicula, id_horario, fila, columna),
            FOREIGN KEY (id_sala, id_pelicula, id_horario, fila, columna) REFERENCES asiento(id_sala, id_pelicula, id_horario, fila, columna),
            FOREIGN KEY (id_cineticket) REFERENCES cineticket(id_cineticket)
        );
    */
    private $pdo;

    /**
     * Constructor del controlador de películas para administración.
     * @param PDO $pdo Conexión PDO a la base de datos.
     * @see https://www.php.net/manual/es/class.pdo.php
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Obtener todas las compras de cine (asientos comprados).
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAll(Request $request, Response $response)
    {
        $table = strtolower(str_replace('Controller', '', (new \ReflectionClass($this))->getShortName()));
        $candidates = ["vista" . $table, "vista_" . $table];

        foreach ($candidates as $view) {
            try {
                $stmt = $this->pdo->query("SELECT * FROM `" . $view . "`");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $response->getBody()->write(json_encode([
                    "success" => true,
                    "data" => $rows,
                ]));

                return $response->withHeader("Content-Type", "application/json");
            } catch (\PDOException $e) {
            }
        }

        $response->getBody()->write(json_encode([
            "success" => false,
            "data" => [],
            "message" => "No view found for table: " . $table,
        ]));

        return $response->withHeader("Content-Type", "application/json")->withStatus(500);
    }

    /**
     * Eliminar un registro por ID.
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

            // Obtener la primera clave como identificador (generalmente la clave primaria)
            $idKey = array_key_first($data);
            $idValue = $data[$idKey];

            if (!$idKey || !$idValue) {
                $response->getBody()->write(json_encode([
                    "success" => false,
                    "message" => "ID field not provided",
                ]));
                return $response->withHeader("Content-Type", "application/json")->withStatus(400);
            }

            // Obtener el nombre de la tabla desde el nombre de la clase
            $table = strtolower(str_replace('Controller', '', (new \ReflectionClass($this))->getShortName()));
            
            // Ejecutar DELETE
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
