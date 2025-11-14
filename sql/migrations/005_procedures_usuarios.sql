DELIMITER //

-- Procedure to register a new user
CREATE PROCEDURE registrarUsuario(
    IN p_nombre VARCHAR(100),
    IN p_apellido VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_password VARCHAR(255),
    IN p_id_rol INT
)
BEGIN
    INSERT INTO usuarios (nombre, apellido, email, password, id_rol)
    VALUES (p_nombre, p_apellido, p_email, p_password, p_id_rol);
END //

-- Procedure to authenticate user
CREATE PROCEDURE autenticarUsuario(
    IN p_email VARCHAR(255),
    IN p_password VARCHAR(255)
)
BEGIN
    SELECT 
        u.id_usuario,
        u.nombre,
        u.apellido,
        u.email,
        u.id_rol,
        r.nombre AS rol_nombre,
        u.estado
    FROM usuarios u
    JOIN roles r ON u.id_rol = r.id_rol
    WHERE u.email = p_email 
    AND u.password = p_password
    AND u.estado = 'activo';
END //

-- Procedure to get user by ID
CREATE PROCEDURE obtenerUsuarioPorId(
    IN p_id_usuario INT
)
BEGIN
    SELECT 
        u.id_usuario,
        u.nombre,
        u.apellido,
        u.email,
        u.id_rol,
        r.nombre AS rol_nombre,
        u.estado,
        u.fecha_registro
    FROM usuarios u
    JOIN roles r ON u.id_rol = r.id_rol
    WHERE u.id_usuario = p_id_usuario;
END //

-- Procedure to update last access time
CREATE PROCEDURE actualizarUltimoAcceso(
    IN p_id_usuario INT
)
BEGIN
    UPDATE usuarios 
    SET ultimo_acceso = CURRENT_TIMESTAMP 
    WHERE id_usuario = p_id_usuario;
END //

-- Procedure to get all users (for admin use)
CREATE PROCEDURE listarUsuarios()
BEGIN
    SELECT 
        u.id_usuario,
        u.nombre,
        u.apellido,
        u.email,
        u.id_rol,
        r.nombre AS rol_nombre,
        u.estado,
        u.fecha_registro,
        u.ultimo_acceso
    FROM usuarios u
    JOIN roles r ON u.id_rol = r.id_rol
    ORDER BY u.fecha_registro DESC;
END //

-- Procedure to update user role (admin use)
CREATE PROCEDURE actualizarRolUsuario(
    IN p_id_usuario INT,
    IN p_id_rol INT
)
BEGIN
    UPDATE usuarios 
    SET id_rol = p_id_rol 
    WHERE id_usuario = p_id_usuario;
END //

-- Procedure to deactivate user (admin use)
CREATE PROCEDURE desactivarUsuario(
    IN p_id_usuario INT
)
BEGIN
    UPDATE usuarios 
    SET estado = 'inactivo' 
    WHERE id_usuario = p_id_usuario;
END //

-- Procedure to activate user (admin use)
CREATE PROCEDURE activarUsuario(
    IN p_id_usuario INT
)
BEGIN
    UPDATE usuarios 
    SET estado = 'activo' 
    WHERE id_usuario = p_id_usuario;
END //

DELIMITER ;