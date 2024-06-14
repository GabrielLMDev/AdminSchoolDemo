<?php
$cookie_value = $_COOKIE['id_student_access']; //obtener cookie.
$cookie_values = json_decode($cookie_value, true); //obtener los valores de la cookie.
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
$cookie_values['student_number'] = '2024061076';
$cookie_values['semester'] = '2';
*/

if ($data && isset($data['option']) && between($data['option'], 1, 3) && $cookie_values['student_number'] && $cookie_values['semester']) {
    switch ($data['option']) {
        case '1':
            getActualGrades($cookie_values['student_number'], $cookie_values['semester']);
            break;

        case '2':
            getGradeGeneral($cookie_values['student_number']);
            break;
    }
} else {
    error_log('Error al recibir dato');
    echo json_encode(['status' => 'error', 'message' => 'Error al recibir dato']);
}

// Función para obtener la calificacion general de una materia.
function getActualGrades($student_number, $semester)
{
    // Incluir el archivo de conexión a la base de datos solo una vez.
    require_once 'db_connection.php';

    $student_number = sanitizeInput($student_number);
    $semester = sanitizeInput($semester);

    // Consulta preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT enrollments.student_number, enrollments.enrollment_id, grades.partial, grades.grade, classes.subject_id, classes.semester, classes.teacher_number, teachers.first_name, teachers.last_name, subjects.name, subjects.type 
    FROM enrollments 
    JOIN grades ON grades.enrollment_id = enrollments.enrollment_id 
    JOIN classes ON classes.class_id = enrollments.class_id 
    JOIN subjects ON subjects.subject_id = classes.subject_id 
    JOIN teachers ON teachers.teacher_number = classes.teacher_number 
    WHERE enrollments.student_number = :student_number AND enrollments.semester = :semester");
    $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos fetchAll() para obtener todos los resultados
    $result = [];
    $subjects = [];

    foreach ($data as $entry) {
        $enrollment_id = $entry['enrollment_id'];

        if (!isset($subjects[$enrollment_id])) {
            $subjects[$enrollment_id] = [
                'name' => $entry['name'],
                'teacher' => $entry['first_name'] . ' ' . $entry['last_name'],
                'grades' => [],
                'type' => $entry['type'],
                'final' => 0,
                'count' => 0
            ];
        }

        $subjects[$enrollment_id]['grades'][$entry['partial']] = floatval($entry['grade']);
        $subjects[$enrollment_id]['final'] += floatval($entry['grade']);
        $subjects[$enrollment_id]['count']++;
    }

    foreach ($subjects as $enrollment_id => $subject) {
        $subject['final'] = $subject['count'] ? $subject['final'] / $subject['count'] : 0;
        $result[] = $subject;
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $result]);
}

function getGradeGeneral($student_number)
{
    // Incluir el archivo de conexión a la base de datos solo una vez.
    require_once 'db_connection.php';

    $student_number = sanitizeInput($student_number);

    // Consulta preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT enrollments.student_number, enrollments.enrollment_id, grades.partial, grades.grade, classes.subject_id, classes.semester, classes.teacher_number, teachers.first_name, teachers.last_name, subjects.name, subjects.type 
    FROM enrollments 
    JOIN grades ON grades.enrollment_id = enrollments.enrollment_id 
    JOIN classes ON classes.class_id = enrollments.class_id 
    JOIN subjects ON subjects.subject_id = classes.subject_id 
    JOIN teachers ON teachers.teacher_number = classes.teacher_number 
    WHERE enrollments.student_number = :student_number");
    $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos fetchAll() para obtener todos los resultados

    $result = [];
    $subjects = [];

    foreach ($data as $entry) {
        $enrollment_id = $entry['enrollment_id'];

        if (!isset($subjects[$enrollment_id])) {
            $subjects[$enrollment_id] = [
                'name' => $entry['name'],
                'teacher' => $entry['first_name'] . ' ' . $entry['last_name'],
                'grades' => [],
                'type' => $entry['type'],
                'final' => 0,
                'count' => 0
            ];
        }

        $subjects[$enrollment_id]['grades'][$entry['partial']] = floatval($entry['grade']);
        $subjects[$enrollment_id]['final'] += floatval($entry['grade']);
        $subjects[$enrollment_id]['count']++;
    }

    foreach ($subjects as $enrollment_id => $subject) {
        $subject['final'] = $subject['count'] ? $subject['final'] / $subject['count'] : 0;
        $result[] = $subject;
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $result]);
}