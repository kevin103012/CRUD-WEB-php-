<?php
include_once "../model/db/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación básica / esto verifica que halla un POST XD
    $id = $_POST['nw_id_alumno'] ?? null;
    $nombre = $_POST['nw_nombre_alumno'] ?? '';
    $promedio = $_POST['nw_promedio'] ?? '';
    $curso = $_POST['nw_curso_nombre'] ?? '';
    $instructor = $_POST['nw_instructor_nombre'] ?? '';
    // si todos las variables tienen valores , entonces , se ejecute esta wbd
    if ($id && $nombre && $promedio && $curso && $instructor) {
        try {
            //id del curso
            $stmtCurso = $db->prepare("SELECT id_curso FROM curso WHERE nombre_curso = :nombre");
            $stmtCurso->execute([':nombre' => $curso]); 
            $rowCurso = $stmtCurso->fetch(PDO::FETCH_ASSOC);
            if (!$rowCurso) throw new Exception("Curso no encontrado");
            $idCurso = $rowCurso['id_curso'];//guarda el valor ENCONTRADO 

            //id instructor
            $stmtInstructor = $db->prepare("SELECT id_instructor FROM instructor WHERE nombre_instructor = :nombre");
            $stmtInstructor->execute([':nombre' => $instructor]);
            $rowInstructor = $stmtInstructor->fetch(PDO::FETCH_ASSOC);
            if (!$rowInstructor) throw new Exception("Instructor no encontrado");
            $idInstructor = $rowInstructor['id_instructor'];//mismo procedimiento , copia y pega :v

            // actualiza mi king
            $stmtNotas = $db->prepare("UPDATE notas SET promedio = :promedio, id_curso = :idCurso, id_instructor = :idInstructor WHERE id_alumno = :id");
            $stmtNotas->execute([
                ':promedio' => $promedio,
                ':idCurso' => $idCurso,
                ':idInstructor' => $idInstructor,
                ':id' => $id
            ]);

            //actualzia la tabla alumnos caumsa
            $stmtAlumno = $db->prepare("UPDATE alumnos SET nombre_alumno = :nombre WHERE id_alumno = :id");
            $stmtAlumno->execute([
                ':nombre' => $nombre,
                ':id' => $id
            ]); // copia y pega copia y pega 

            echo "Actualización exitosa.";
        } catch (Exception $e) {
            echo "Error al actualizar: " . $e->getMessage();
        }
    } else {
        echo "Faltan datos para actualizar.";
    }
} else {
    echo "Acceso no permitido.";
}
?>





















