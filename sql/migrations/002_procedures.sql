DROP PROCEDURE IF EXISTS insertarCiudad;
DROP PROCEDURE IF EXISTS insertarCine;
DROP PROCEDURE IF EXISTS insertarSala;
DROP PROCEDURE IF EXISTS insertarFormato;
DROP PROCEDURE IF EXISTS insertarPelicula;
DROP PROCEDURE IF EXISTS insertarFuncion;
DROP PROCEDURE IF EXISTS insertarHorario;
DROP PROCEDURE IF EXISTS insertarEstado;
DROP PROCEDURE IF EXISTS insertarPeliculaFormato;

DELIMITER //
CREATE PROCEDURE insertarCiudad(IN nombre VARCHAR(20))
BEGIN
    INSERT INTO ciudad(nombre) VALUES (nombre);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarCine(IN nombre VARCHAR(20), IN id_ciudad INT)
BEGIN
    INSERT INTO cine(nombre, id_ciudad) VALUES (nombre, id_ciudad);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarFormato(IN nombre VARCHAR(20))
BEGIN
    INSERT INTO formato(nombre) VALUES (nombre);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE insertarSala(IN filas INT, IN columnas INT, IN id_formato INT, IN id_cine INT)
BEGIN
    INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (filas, columnas, id_formato, id_cine);
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
