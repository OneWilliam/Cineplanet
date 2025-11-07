USE cineplanet;

DROP PROCEDURE IF EXISTS insertarCiudad;
DROP PROCEDURE IF EXISTS insertarCine;
DROP PROCEDURE IF EXISTS insertarSala;
DROP PROCEDURE IF EXISTS insertarFormato;
DROP PROCEDURE IF EXISTS insertarPelicula;
DROP PROCEDURE IF EXISTS insertarFuncion;
DROP PROCEDURE IF EXISTS insertarHorario;
DROP PROCEDURE IF EXISTS insertarPeliculaFormato;

delimiter //
CREATE PROCEDURE insertarCiudad(IN nombre VARCHAR(20))
BEGIN
	INSERT ciudad(nombre) VALUES (nombre);
END//

CREATE PROCEDURE insertarCine(IN nombre VARCHAR(20), IN id_ciudad INT)
BEGIN
	INSERT cine(nombre, id_ciudad) VALUES (nombre, id_ciudad);
END//

CREATE PROCEDURE insertarFormato(IN nombre VARCHAR(20))
BEGIN
	INSERT formato(nombre) VALUES (nombre);
END//

CREATE PROCEDURE insertarSala(IN filas INT, IN columnas INT, IN id_formato INT, id_cine INT)
BEGIN
	INSERT sala(filas, columnas, id_formato, id_cine) VALUES (filas, columnas, id_formato, id_cine);
END//

CREATE PROCEDURE insertarPelicula(IN nombre VARCHAR(20), IN duracion INT)
BEGIN
	INSERT pelicula(nombre, duracion) VALUES (nombre, duracion);
END//

CREATE PROCEDURE insertarHorario(IN hora_inicio TIME)
BEGIN
	INSERT horario(hora_inicio) VALUES (hora_inicio);
END//

CREATE PROCEDURE insertarFuncion(IN id_sala INT, IN id_pelicula INT, IN id_horario INT, IN numero_asientos INT)
BEGIN
	INSERT funcion(id_sala, id_pelicula, id_horario, numero_asientos) VALUES (id_sala, id_pelicula, id_horario, numero_asientos);
END//

CREATE PROCEDURE insertarEstado(IN nombre VARCHAR(20))
BEGIN
	INSERT estado(nombre) VALUES (nombre);
END//

CREATE PROCEDURE insertarPeliculaFormato(IN id_pelicula INT, IN id_formato INT, IN precio INT)
BEGIN
	INSERT peliculaformato(id_formato, id_pelicula, precio) VALUES (id_formato, id_pelicula, precio);
END//
delimiter ;
