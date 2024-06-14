<?php
// Incluir el archivo de conexión a la base de datos solo una vez.
require_once 'db_connection.php';

// Función para sanitizar los datos de entrada (Etiquetas HTML)
function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Leer el contenido JSON de la solicitud
$json = file_get_contents('php://input');

// Decodificar el JSON a un array asociativo de PHP
$data = json_decode($json, true);

if ($data && isset($data['student_number']) && isset($data['password'])) {
    // Recibir y sanitizar el número de control del formulario
    $student_number = sanitizeInput($data['student_number']);
    $password = sanitizeInput($data['password']);

    // Validar que el número de control es un número válido (opcional, según los requisitos)
    if (filter_var($student_number, FILTER_VALIDATE_INT) === false) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid control number']));
    }

    try {
        // Ejemplo de consulta preparada para evitar inyección SQL
        $stmt = $conn->prepare("SELECT access_student.student_number, access_student.password, students.semester
        FROM access_student 
        JOIN students
        ON students.student_number = access_student.student_number
        WHERE access_student.student_number = :student_number");
        $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch();

        if ($student) {
            // Verificar las contraseñas.
            if (password_verify($password, $student['password'])) {
                // Crear cookie de acceso.
                $cookie_name = "id_student_access";
                $cookie_values = array(
                    "student_number" => $student['student_number'],
                    "semester" => $student['semester'],
                );
                $cookie_value = json_encode($cookie_values);
                $expiration_time = time() + (86400 * 10); // La cookie dura 10 días
                $path = "/"; // Ruta (disponible en todo el sitio)

                setcookie($cookie_name, $cookie_value, $expiration_time, $path);
                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
    } catch (PDOException $e) {
        // Manejo de errores en las consultas
        error_log('Query Failed: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error retrieving data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Form not sent or missing control number']);
}