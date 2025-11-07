USE cineplanet;

CALL insertarCiudad("Lima");
CALL insertarCiudad("Tacna");
CALL insertarCiudad("Arequipa");
CALL insertarCiudad("Ilo");
CALL insertarCiudad("Moquegua");
CALL insertarCiudad("Cuzco");
CALL insertarCiudad("Trujillo");
CALL insertarCiudad("Puno");

CALL insertarCine("CP Tacna", 2);
CALL insertarCine("CP Mall Plaza", 1);
CALL insertarCine("CP Miraflores", 1);
CALL insertarCine("CP La Victoria", 1);
CALL insertarCine("CP Real Plaza", 1);
CALL insertarCine("CP Moquegua", 5);

CALL insertarFormato("2D Regular");
CALL insertarFormato("3D Regular");
CALL insertarFormato("3D IMax");
CALL insertarFormato("3D Xtreme");

CALL insertarSala(2, 2, 1, 1);
CALL insertarSala(2, 2, 1, 2);
CALL insertarSala(2, 2, 1, 2);
CALL insertarSala(2, 2, 2, 2);
CALL insertarSala(2, 2, 3, 2);
CALL insertarSala(2, 2, 1, 3);
CALL insertarSala(2, 2, 2, 3);
CALL insertarSala(2, 2, 1, 4);
CALL insertarSala(2, 2, 1, 5);

CALL insertarPelicula("Interestelar", 90);
CALL insertarPelicula("Moonlighten", 100);
CALL insertarPelicula("The Queen", 90);
CALL insertarPelicula("The Pianist", 120);

CALL insertarHorario("10:00:00");
CALL insertarHorario("12:30:00");

CALL insertarPeliculaFormato(1, 1, 20);
CALL insertarPeliculaFormato(1, 2, 25);
CALL insertarPeliculaFormato(1, 3, 30);
CALL insertarPeliculaFormato(2, 1, 10);
CALL insertarPeliculaFormato(3, 1, 15);

CALL insertarEstado("Libre");
CALL insertarEstado("Ocupado");

CALL insertarFuncion(1, 1, 1, 4);
CALL insertarFuncion(2, 2, 1, 4);
CALL insertarFuncion(1, 1, 2, 4);

SELECT * from ciudad
INNER JOIN cine ON cine.id_ciudad = ciudad.id_ciudad
INNER JOIN sala ON sala.id_cine = cine.id_cine
INNER JOIN formato ON formato.id_formato = sala.id_formato
INNER JOIN funcion ON funcion.id_sala = sala.id_sala
INNER JOIN pelicula ON pelicula.id_pelicula = funcion.id_pelicula
INNER JOIN horario ON horario.id_horario = funcion.id_horario;