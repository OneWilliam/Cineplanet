DROP PROCEDURE IF EXISTS listarPeliculas;

DELIMITER //
CREATE PROCEDURE listarPeliculas()
BEGIN
    SELECT id_pelicula AS pelicula_id, nombre, duracion
    FROM pelicula
    ORDER BY nombre
    LIMIT 20;
END //
DELIMITER ;
