CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Insert default roles
INSERT INTO ciudad(nombre) VALUES ("Tacna"), ("Lima");
INSERT INTO cine(nombre, id_ciudad) VALUES ("CP Tacna", 1), ("CP Mall Plaza", 2);
INSERT INTO empleado(nombre,
    apellido,
    dni,
    celular,
    id_cine) VALUES ("Juan", "Tomas", "99999999", "912345678", 1), ("Tammer", "Montly", "00000000", "12345678", 1);
    
INSERT INTO admin(nombre, password) VALUES ("admin1", 1), ("admin2", 2);

INSERT INTO roles (nombre, descripcion) VALUES 
('cliente', 'Usuario cliente regular'),
('admin', 'Usuario administrador con acceso completo');

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    estado ENUM('activo', 'inactivo', 'bloqueado') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

-- Add foreign key to relate usuarios with clientes if needed
-- For now, keeping them separate as they may have different purposes