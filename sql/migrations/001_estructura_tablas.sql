CREATE TABLE ciudad (
	id_ciudad INT AUTO_INCREMENT,
    nombre VARCHAR(20),
    PRIMARY KEY (id_ciudad)
);

CREATE TABLE cine (
	id_cine INT AUTO_INCREMENT,
    nombre VARCHAR(20),
    id_ciudad INT NOT NULL,
    PRIMARY KEY (id_cine),
    FOREIGN KEY (id_ciudad) REFERENCES ciudad(id_ciudad)
);

CREATE TABLE formato (
	id_formato INT AUTO_INCREMENT,
    nombre VARCHAR(20),
    PRIMARY KEY (id_formato)
);

CREATE TABLE pelicula (
	id_pelicula INT AUTO_INCREMENT,
    nombre VARCHAR(20),
    duracion INT NOT NULL,
    PRIMARY KEY (id_pelicula)
);

CREATE TABLE horario (
	id_horario INT AUTO_INCREMENT,
    hora_inicio TIME,
    PRIMARY KEY (id_horario)
);

CREATE TABLE estado (
	id_estado INT AUTO_INCREMENT,
    estado VARCHAR(20),
    PRIMARY KEY (id_estado)
);

CREATE TABLE sala (
	id_sala INT AUTO_INCREMENT,
    filas INT,
    columnas INT,
    id_cine INT NOT NULL,
    id_formato INT NOT NULL,
    PRIMARY KEY (id_sala),
    FOREIGN KEY (id_formato) REFERENCES formato(id_formato),
    FOREIGN KEY (id_cine) REFERENCES cine(id_cine)
);

CREATE TABLE peliculaformato (
	id_pelicula INT NOT NULL,
    id_formato INT NOT NULL,
    precio INT NOT NULL,
    PRIMARY KEY (id_pelicula, id_formato),
    FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (id_formato) REFERENCES formato(id_formato)
);

CREATE TABLE funcion (
	id_sala INT NOT NULL,
    id_pelicula INT NOT NULL,
    id_horario INT NOT NULL,
    numero_asientos INT,
    PRIMARY KEY (id_sala, id_pelicula, id_horario),
    FOREIGN KEY (id_sala) REFERENCES sala(id_sala),
    FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (id_horario) REFERENCES horario(id_horario)
);

CREATE TABLE asiento (
	id_sala INT NOT NULL,
    id_pelicula INT NOT NULL,
    id_horario INT NOT NULL,
    fila INT NOT NULL,
    columna INT NOT NULL,
    id_estado INT NOT NULL,
    PRIMARY KEY (id_sala, id_pelicula, id_horario, fila, columna),
    FOREIGN KEY (id_sala) REFERENCES sala(id_sala),
    FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (id_horario) REFERENCES horario(id_horario),
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado)
);

CREATE TABLE dulceria (
	id_dulceria INT NOT NULL,
    id_cine INT NOT NULL,
    PRIMARY KEY (id_dulceria),
    FOREIGN KEY (id_cine) REFERENCES cine(id_cine)
);

CREATE TABLE categoria (
	id_categoria INT NOT NULL,
    nombre VARCHAR(20),
    PRIMARY KEY (id_categoria)
);

CREATE TABLE dulceriacategoria (
	id_categoria INT NOT NULL,
    id_dulceria INT NOT NULL,
    PRIMARY KEY (id_categoria, id_dulceria),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
    FOREIGN KEY (id_dulceria) REFERENCES dulceria(id_dulceria)
);

CREATE TABLE dulce (
	id_dulce INT NOT NULL,
    nombre VARCHAR(20),
    precio INT,
    id_categoria INT NOT NULL,
    PRIMARY KEY (id_dulce),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE dulceriaticket (
	id_dulceriaticket INT NOT NULL,
    precio_dulceriaticket INT NOT NULL,
    PRIMARY KEY (id_dulceriaticket)
);

CREATE TABLE compradulceria (
	id_dulceriaticket INT NOT NULL,
    id_dulce INT NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY (id_dulceriaticket, id_dulce),
    FOREIGN KEY (id_dulceriaticket) REFERENCES dulceriaticket(id_dulceriaticket),
	FOREIGN KEY (id_dulce) REFERENCES dulce(id_dulce)
);

CREATE TABLE cineticket (
	id_cineticket INT NOT NULL,
    precio_cineticket INT NOT NULL,
    PRIMARY KEY (id_cineticket)
);

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

CREATE TABLE cliente (
	id_cliente INT NOT NULL,
    nombre VARCHAR(20),
    apellido VARCHAR(20),
    dni VARCHAR(20),
    PRIMARY KEY (id_cliente)
);

CREATE TABLE boleta (
	id_boleta INT AUTO_INCREMENT,
    id_cineticket INT NOT NULL,
    id_dulceriaticket INT NOT NULL,
    id_cliente INT NOT NULL,
    preciototal INT NOT NULL,
    PRIMARY KEY (id_boleta),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_cineticket) REFERENCES cineticket(id_cineticket),
    FOREIGN KEY (id_dulceriaticket) REFERENCES dulceriaticket(id_dulceriaticket)
);