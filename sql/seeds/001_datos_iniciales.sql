call insertarCiudad('Arequipa');
call insertarCiudad('Cajamarca');
call insertarCiudad('Chiclayo');
call insertarCiudad('Cuzco');
call insertarCiudad('Huancayo');
call insertarCiudad('Huanuco');
call insertarCiudad('Juliaca');
call insertarCiudad('Lima');
call insertarCiudad('Piura');
call insertarCiudad('Pucallpa');
call insertarCiudad('Puno');
call insertarCiudad('Tacna');
call insertarCiudad('Trujillo');

call insertarCine('CP Tacna', 'Tacna');
call insertarCine('CP Mall Plaza','Lima');
call insertarCine('CP Miraflores', 'Lima');
call insertarCine('CP La Victoria', 'Arequipa');
call insertarCine('CP Real Plaza', 'Piura');
call insertarCine('CP Cuzco', 'Cuzco');

call insertarFormato('2D Regular');
call insertarFormato('3D Regular');
call insertarFormato('3D IMax');
call insertarFormato('3D Xtreme');

call insertarSala(2, 2, 1, 'CP Tacna');
call insertarSala(2, 2, 1, 'CP Tacna');
call insertarSala(2, 2, 1, 'CP Miraflores');
call insertarSala(2, 2, 2, 'CP La Victoria');
call insertarSala(2, 2, 3, 'CP Real Plaza');
call insertarSala(2, 2, 1, 'CP Cuzco');
call insertarSala(2, 2, 2, 'CP Tacna');
call insertarSala(2, 2, 1, 'CP Tacna');
call insertarSala(2, 2, 1, 'CP Tacna');

call insertarPelicula('Interestelar', 90);
call insertarPelicula('Moonlighten', 100);
call insertarPelicula('The Queen', 90);
call insertarPelicula('The Pianist', 120);

call insertarHorario('10:00:00');
call insertarHorario('12:30:00');

call insertarPeliculaformato(1, 1, 20);
call insertarPeliculaformato(1, 2, 25);
call insertarPeliculaformato(1, 3, 30);
call insertarPeliculaformato(2, 1, 10);
call insertarPeliculaformato(3, 1, 15);

call insertarEstado('Libre');
call insertarEstado('Ocupado');

call insertarFuncion(1, 1, 1, 4);
call insertarFuncion(2, 2, 1, 4);
call insertarFuncion(1, 1, 2, 4);
