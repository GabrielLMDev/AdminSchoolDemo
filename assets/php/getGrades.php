<?php
// Función para sanitizar los datos de entrada (Etiquetas HTML)
function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Función para devolver un numero entre dos rangos.
function between($value, $min, $max)
{
    return $value >= $min && $value <= $max;
}

// Leer el contenido JSON de la solicitud
$json = file_get_contents('php://input');

// Decodificar el JSON a un array asociativo de PHP
$data = json_decode($json, true);

/*Variables para debug.
$data['option'] = '1';
$data['enrollment_id'] = '1';
*/

if ($data && isset($data['option']) && isset($data['enrollment_id']) && between($data['option'], 1, 3)) {
    switch ($data['option']) {
        case '1':
            getGradePerSubject($data['enrollment_id']);
            break;

        case '2':
            getGradeGeneral($data['enrollment_id']);
            break;
    }
} else {
    error_log('Error al recibir dato');
    echo json_encode(['status' => 'error', 'message' => 'Error al recibir dato']);
}

// Función para obtener la calificacion general de una materia.
function getGradePerSubject($enrollment_id)
{
    // Incluir el archivo de conexión a la base de datos solo una vez.
    require_once 'db_connection.php';

    $enrollment_id = sanitizeInput($enrollment_id);

    // Consulta preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT *, (SELECT SUM(`grade`) 
    FROM `grades` 
    WHERE `enrollment_id` = :enrollment_id_total) as total_grades 
    FROM `grades` 
    WHERE `enrollment_id` = :enrollment_id");
    $stmt->bindParam(':enrollment_id_total', $enrollment_id, PDO::PARAM_STR);
    $stmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_STR);
    $stmt->execute();
    $dataAfter = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos fetchAll() para obtener todos los resultados
    header('Content-Type: application/json');
    $dataAfter['status'] = 'success';
    echo json_encode($dataAfter);
}

function getGradeGeneral($enrollment_id)
{
    // Incluir el archivo de conexión a la base de datos solo una vez.
    require_once 'db_connection.php';

    $enrollment_id = sanitizeInput($enrollment_id);

    // Consulta preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT AVG(`grade`) AS grade_general FROM `grades` WHERE `enrollment_id` >= 1 && `enrollment_id` <= 6");
    $stmt->execute();
    $dataAfter = $stmt->fetch(PDO::FETCH_ASSOC); // Usamos fetch para obtener el resultado.
    header('Content-Type: application/json');
    $dataAfter['status'] = 'success';
    echo json_encode($dataAfter);
}
