-- Seed for initial admin users
-- Passwords are in plain text for development purposes (in production, these should be hashed)

-- Create admin user
CALL registrarUsuario('Admin', 'Cineplanet', 'admin@cineplanet.com', 'admin123', 2); -- 2 = admin role

-- Create additional admin user
CALL registrarUsuario('Super', 'Usuario', 'superadmin@cineplanet.com', 'super123', 2); -- 2 = admin role

-- Create regular client user for testing
CALL registrarUsuario('Juan', 'Pérez', 'cliente@cineplanet.com', 'cliente123', 1); -- 1 = cliente role