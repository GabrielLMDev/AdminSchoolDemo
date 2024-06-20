<?php
$cookie_value = $_COOKIE['id_student_access']; //obtener cookie.
$cookie_values = json_decode($cookie_value, true); //obtener los valores de la cookie.

// Función para sanitizar los datos de entrada (Etiquetas HTML)
function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

/*Variables para debug.
$cookie_values['student_number'] = '2024061076';
*/

if ($cookie_values['student_number']) {
    getGeneralAttendance($cookie_values['student_number']);
} else {
    error_log('Error al recibir dato');
    echo json_encode(['status' => 'error', 'message' => 'Error al recibir dato']);
}

function getGeneralAttendance($student_number)
{
    // Incluir el archivo de conexión a la base de datos solo una vez.
    require_once 'db_connection.php';

    $student_number = sanitizeInput($student_number);

    // Consulta preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT 
    COUNT(grades.attendance) AS attendance_general,
    SUM(grades.attendance) AS total_attendance,
    (SUM(grades.attendance) / COUNT(grades.attendance) * 100.0 / 20.0) AS percent
    FROM enrollments
    JOIN grades ON grades.enrollment_id = enrollments.enrollment_id
    WHERE enrollments.student_number = :student_number");
    $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Usamos fetch para obtener el resultado.

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'percent_attendance' => $data['percent']]);
}
