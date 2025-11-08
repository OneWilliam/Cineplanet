INSERT INTO ciudad(nombre) VALUES ('Lima');
INSERT INTO ciudad(nombre) VALUES ('Tacna');
INSERT INTO ciudad(nombre) VALUES ('Arequipa');
INSERT INTO ciudad(nombre) VALUES ('Ilo');
INSERT INTO ciudad(nombre) VALUES ('Moquegua');
INSERT INTO ciudad(nombre) VALUES ('Cuzco');
INSERT INTO ciudad(nombre) VALUES ('Trujillo');
INSERT INTO ciudad(nombre) VALUES ('Puno');

INSERT INTO cine(nombre, id_ciudad) VALUES ('CP Tacna', 2);
INSERT INTO cine(nombre, id_ciudad) VALUES ('CP Mall Plaza', 1);
INSERT INTO cine(nombre, id_ciudad) VALUES ('CP Miraflores', 1);
INSERT INTO cine(nombre, id_ciudad) VALUES ('CP La Victoria', 1);
INSERT INTO cine(nombre, id_ciudad) VALUES ('CP Real Plaza', 1);
INSERT INTO cine(nombre, id_ciudad) VALUES ('CP Moquegua', 5);

INSERT INTO formato(nombre) VALUES ('2D Regular');
INSERT INTO formato(nombre) VALUES ('3D Regular');
INSERT INTO formato(nombre) VALUES ('3D IMax');
INSERT INTO formato(nombre) VALUES ('3D Xtreme');

INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 1, 1);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 1, 2);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 1, 2);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 2, 2);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 3, 2);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 1, 3);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 2, 3);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 1, 4);
INSERT INTO sala(filas, columnas, id_formato, id_cine) VALUES (2, 2, 1, 5);

INSERT INTO pelicula(nombre, duracion) VALUES ('Interestelar', 90);
INSERT INTO pelicula(nombre, duracion) VALUES ('Moonlighten', 100);
INSERT INTO pelicula(nombre, duracion) VALUES ('The Queen', 90);
INSERT INTO pelicula(nombre, duracion) VALUES ('The Pianist', 120);

INSERT INTO horario(hora_inicio) VALUES ('10:00:00');
INSERT INTO horario(hora_inicio) VALUES ('12:30:00');

INSERT INTO peliculaformato(id_pelicula, id_formato, precio) VALUES (1, 1, 20);
INSERT INTO peliculaformato(id_pelicula, id_formato, precio) VALUES (1, 2, 25);
INSERT INTO peliculaformato(id_pelicula, id_formato, precio) VALUES (1, 3, 30);
INSERT INTO peliculaformato(id_pelicula, id_formato, precio) VALUES (2, 1, 10);
INSERT INTO peliculaformato(id_pelicula, id_formato, precio) VALUES (3, 1, 15);

INSERT INTO estado(estado) VALUES ('Libre');
INSERT INTO estado(estado) VALUES ('Ocupado');

INSERT INTO funcion(id_sala, id_pelicula, id_horario, numero_asientos) VALUES (1, 1, 1, 4);
INSERT INTO funcion(id_sala, id_pelicula, id_horario, numero_asientos) VALUES (2, 2, 1, 4);
INSERT INTO funcion(id_sala, id_pelicula, id_horario, numero_asientos) VALUES (1, 1, 2, 4);
