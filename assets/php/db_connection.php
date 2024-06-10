<?php
// Configuración de la base de datos
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'school_system_database';
// Configuración adicional para el manejo de errores y el modo de retorno de datos
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Establece el modo de recuperación de datos por defecto como asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación de declaraciones preparadas
];
try {
    // Intento de conexión a la base de datos con las opciones configuradas
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    // Manejo de errores en caso de fallo en la conexión
    error_log('Connection Failed: ' . $e->getMessage()); // Log de errores
    die('Database Connection Failed'); // Mensaje genérico para el usuario
}
