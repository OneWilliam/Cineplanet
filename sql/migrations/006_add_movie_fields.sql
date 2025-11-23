-- Agregar columnas a la tabla pelicula
ALTER TABLE pelicula 
ADD COLUMN IF NOT EXISTS imagen VARCHAR(255) DEFAULT 'default.jpg',
ADD COLUMN IF NOT EXISTS descripcion TEXT,
ADD COLUMN IF NOT EXISTS clasificacion VARCHAR(10) DEFAULT 'ATP';

-- Actualizar películas existentes con imágenes de ejemplo
UPDATE pelicula SET imagen = 'interestelar.jpg', descripcion = 'Una épica aventura espacial', clasificacion = '+13' WHERE id_pelicula = 1;
UPDATE pelicula SET imagen = 'moonlight.jpg', descripcion = 'Drama íntimo y conmovedor', clasificacion = '+16' WHERE id_pelicula = 2;
UPDATE pelicula SET imagen = 'the-queen.jpg', descripcion = 'La historia de la Reina Isabel', clasificacion = 'ATP' WHERE id_pelicula = 3;
UPDATE pelicula SET imagen = 'the-pianist.jpg', descripcion = 'La historia de un pianista durante la guerra', clasificacion = '+13' WHERE id_pelicula = 4;
