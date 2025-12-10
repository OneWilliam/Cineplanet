
DELIMITER //
CREATE PROCEDURE eliminarCiudad(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM ciudad WHERE id_ciudad = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('ciudad — id_ciudad=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarCine(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM cine WHERE id_cine = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('cine — id_cine=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarFormato(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM formato WHERE id_formato = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('formato — id_formato=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarPelicula(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM pelicula WHERE id_pelicula = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('pelicula — id_pelicula=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarHorario(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM horario WHERE id_horario = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('horario — id_horario=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarEstado(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM estado WHERE id_estado = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('estado — id_estado=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarSala(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM sala WHERE id_sala = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('sala — id_sala=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarPeliculaFormato(IN pid_pelicula INT, IN pid_formato INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM peliculaformato 
        WHERE id_pelicula = pid_pelicula AND id_formato = pid_formato;

        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES (
            'DELETE', 
            CONCAT('peliculaformato — id_pelicula=', pid_pelicula, ' id_formato=', pid_formato),
            NOW()
        );
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarFuncion(IN psala INT, IN ppeli INT, IN phorario INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM funcion 
        WHERE id_sala = psala AND id_pelicula = ppeli AND id_horario = phorario;

        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES (
            'DELETE', 
            CONCAT('funcion — sala=', psala, ' pelicula=', ppeli, ' horario=', phorario),
            NOW()
        );
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarAsiento(IN psala INT, IN ppeli INT, IN phorario INT, IN pfila INT, IN pcol INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM asiento 
        WHERE id_sala = psala AND id_pelicula = ppeli 
          AND id_horario = phorario AND fila = pfila AND columna = pcol;

        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES (
            'DELETE', 
            CONCAT('asiento — sala=', psala, ' pelicula=', ppeli, ' horario=', phorario,
                   ' fila=', pfila, ' columna=', pcol),
            NOW()
        );
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarDulceria(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM dulceria WHERE id_dulceria = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('dulceria — id_dulceria=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarCategoria(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM categoria WHERE id_categoria = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('categoria — id_categoria=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarDulceriaCategoria(IN pid_cat INT, IN pid_dul INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM dulceriacategoria 
        WHERE id_categoria = pid_cat AND id_dulceria = pid_dul;

        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES (
            'DELETE', 
            CONCAT('dulceriacategoria — id_categoria=', pid_cat, ' id_dulceria=', pid_dul),
            NOW()
        );
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarDulce(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM dulce WHERE id_dulce = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('dulce — id_dulce=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarDulceriaTicket(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM dulceriaticket WHERE id_dulceriaticket = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('dulceriaticket — id_dulceriaticket=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarCompraDulceria(IN pid_ticket INT, IN pid_dulce INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM compradulceria 
        WHERE id_dulceriaticket = pid_ticket AND id_dulce = pid_dulce;

        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES (
            'DELETE', 
            CONCAT('compradulceria — id_ticket=', pid_ticket, ' id_dulce=', pid_dulce),
            NOW()
        );
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarCineTicket(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM cineticket WHERE id_cineticket = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('cineticket — id_cineticket=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarCompraCine(IN psala INT, IN ppeli INT, IN phorario INT, IN pfila INT, IN pcol INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM compracine 
        WHERE id_sala = psala AND id_pelicula = ppeli AND id_horario = phorario 
              AND fila = pfila AND columna = pcol;

        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES (
            'DELETE', 
            CONCAT('compracine — sala=', psala, ' peli=', ppeli, ' horario=', phorario,
                   ' fila=', pfila, ' col=', pcol),
            NOW()
        );
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarCliente(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM cliente WHERE id_cliente = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('cliente — id_cliente=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarBoleta(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM boleta WHERE id_boleta = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('boleta — id_boleta=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarEmpleado(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM empleado WHERE id_empleado = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('empleado — id_empleado=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminarAdmin(IN pid INT)
BEGIN
    SET autocommit = 0;
    START TRANSACTION;
        DELETE FROM admin WHERE id_admin = pid;
        INSERT INTO adminlog (metodo, informacion, hora)
        VALUES ('DELETE', CONCAT('admin — id_admin=', pid), NOW());
    COMMIT;
END //
DELIMITER ;

