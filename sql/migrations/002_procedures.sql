DELIMITER //
CREATE PROCEDURE insertarCiudad(IN nombre VARCHAR(20))
BEGIN
    INSERT INTO ciudad(nombre) VALUES (nombre);
END //
DELIMITER ;

DELIMITER //
CREATE FUNCTION buscarCiudadId(p_nombre VARCHAR(20)) RETURNS INT
BEGIN
    DECLARE r_id int;
    SELECT id_ciudad into r_id from ciudad d
    WHERE LOWER(TRIM(d.nombre)) = LOWER(TRIM(p_nombre))
    LIMIT 1;

    RETURN r_id;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarCine(IN c_nombre VARCHAR(20), IN ciudad VARCHAR(20))
BEGIN
    DECLARE v_id_ciudad INT;
    SET v_id_ciudad = buscarCiudadId(ciudad);

    INSERT INTO cine(nombre, id_ciudad) VALUES (c_nombre, v_id_ciudad);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarFormato(IN nombre VARCHAR(20))
BEGIN
    INSERT INTO formato(nombre) VALUES (nombre);
END //
DELIMITER ;


DELIMITER //
CREATE FUNCTION buscarCineId(p_nombre VARCHAR(20)) RETURNS INT
BEGIN
    DECLARE r_id int;
    SELECT id_cine into r_id from cine d
    WHERE LOWER(TRIM(d.nombre)) = LOWER(TRIM(p_nombre))
    LIMIT 1;

    RETURN r_id;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarSala(IN filas INT, IN columnas INT, IN id_formato INT, IN cine_nombre VARCHAR(20))
BEGIN
    DECLARE v_id_cine INT;
    SET v_id_cine = buscarCineId(cine_nombre);
    INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (filas, columnas, id_formato, v_id_cine);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarPelicula(IN nombre VARCHAR(20), IN duracion INT)
BEGIN
    INSERT INTO pelicula(nombre, duracion) VALUES (nombre, duracion);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarHorario(IN hora_inicio TIME)
BEGIN
    INSERT INTO horario(hora_inicio) VALUES (hora_inicio);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarFuncion(IN id_sala INT, IN id_pelicula INT, IN id_horario INT, IN numero_asientos INT)
BEGIN
    INSERT INTO funcion(id_sala, id_pelicula, id_horario, numero_asientos) VALUES (id_sala, id_pelicula, id_horario, numero_asientos);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarEstado(IN nombre VARCHAR(20))
BEGIN
    INSERT INTO estado(estado) VALUES (nombre);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarPeliculaFormato(IN id_pelicula INT, IN id_formato INT, IN precio INT)
BEGIN
    INSERT INTO peliculaformato(id_pelicula, id_formato, precio) VALUES (id_pelicula, id_formato, precio);
END //
DELIMITER ;
