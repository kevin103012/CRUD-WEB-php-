<?php


include_once "../../model/db/dbconnect.php";
session_start();

// para que solo el DIRECTOR  tenga el poder de eliminar y editar .... wa
if (!isset($_SESSION['director_login'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) { // GET = obtener , porsiaca
    $id_alumno = $_GET['id'];

    //elimina las notas del alumno , basandose en el id xd
    $stmt1 = $db->prepare("DELETE FROM notas WHERE id_alumno = :id");
    $stmt1->bindParam(':id', $id_alumno);
    $stmt1->execute();

    $stmt2 = $db->prepare("DELETE FROM alumnos WHERE id_alumno = :id");
    $stmt2->bindParam(':id', $id_alumno);
    $stmt2->execute();
    //una vez realizada la operacion , se regresa a la tabla
    header("Location: ../director_view.php"); 
    exit();
} else {
    echo "ID NO COHERENTE , ute no existe ";
}
?>


?>

