<?php
// Eliminar la cookie estableciendo una fecha de expiración en el pasado
setcookie("id_student_access", "", time() - 3600, "/");

// Redirigir al formulario de login
header("Location: ../../login.php");
exit();
