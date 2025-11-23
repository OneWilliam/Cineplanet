<?php

// Datos de conexión (los que te proporcionaron)
$host = "ftp.arnolab.net";
$user = "u914095763_g8";
$pass = "bd954yUB";
$dbname = "u914095763_g8";
$port = 3306;

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Verificar errores de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error . "\n");
}

echo "Conexión exitosa a la base de datos.\n";

// --- INGRESA TU CONSULTA SQL AQUÍ ---
$sql = "SELECT * FROM usuarios LIMIT 5";
// Asegúrate de cambiar 'nombre_de_tu_tabla' por una tabla real que exista.

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "Resultados encontrados:\n";
        // Iterar sobre los resultados
        while ($row = $result->fetch_assoc()) {
            // Ajusta esto para mostrar las columnas específicas de tu tabla
            print_r($row);
        }
    } else {
        echo "La consulta no devolvió resultados.\n";
    }
} else {
    echo "Error en la consulta SQL: " . $conn->error . "\n";
}

// Cerrar conexión
$conn->close();

?>
