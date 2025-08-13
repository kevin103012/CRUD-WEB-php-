
<?php
include_once "../model/db/dbconnect.php";
session_start();
if (!isset($_SESSION['alumno_login'])) {
    header("location: ../index.php");
}
if (isset($_SESSION['director_login'])) {
    header("location: ../view_director/director_view.php");
}

$promedio = null;
$mensaje = null;

if (isset($_POST['enviar'])) {
    $curso = $_POST['cursos'] ?? null;
    $id = $_POST['id_alumno'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $nota1 = $_POST['nota1'] ?? null;
    $nota2 = $_POST['nota2'] ?? null;
    $nota3 = $_POST['nota3'] ?? null;
    $nota4 = $_POST['nota4'] ?? null;


    if ($id && $nombre && $nota1 >= 0 && $nota2 >= 0 && $nota3 >= 0 && $nota4 >= 0) {
        $promedio = round(($nota1 + $nota2 + $nota3 + $nota4) / 4, 2);
        ///esta parte es para almacenar el id del curso ingresado p mi king
        $stmt = $db->prepare("SELECT id_curso FROM curso WHERE nombre_curso = ? LIMIT 1;");
        $stmt->bindValue(1,$curso);
        $stmt->execute();
        $idCurso = $stmt->fetchColumn();

        /// esto es para el instructor manito
        $stmt = $db->prepare("SELECT id_instructor FROM instructor WHERE id_curso = ? LIMIT 1;");
        $stmt->bindValue(1,$idCurso);
        $stmt->execute();
        $idProfe = $stmt->fetchColumn();
       
        // pal alumno 
        $stmt = $db->prepare("INSERT INTO alumnos (id_alumno, nombre_alumno)
                                    VALUES (?, ?);");
        $stmt->bindValue(1,$id);
        $stmt->bindValue(2,$nombre);
        $stmt->execute();


        // PA LAS NOTAS , GAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        $stmt = $db->prepare("INSERT INTO notas (nota1,nota2,nota3,nota4,promedio,id_curso,id_alumno,id_instructor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                                    ");
        
        $stmt->bindValue(1,$nota1);
        $stmt->bindValue(2,$nota2);
        $stmt->bindValue(3,$nota3);
        $stmt->bindValue(4,$nota4);
        $stmt->bindValue(5,$promedio);
        $stmt->bindValue(6,$idCurso);
        $stmt->bindValue(7,$id);
        $stmt->bindValue(8,$idProfe);
        $stmt->execute();
        
    } else {
        $mensaje = "Por favor, complete todos los campos correctamente.";
    }
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Alumno</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    

    <h2>PANEL DEL ALUMNO</h2>

    


    <div class="contenedor">
        <form method="POST" action="">
            <select name="cursos" id="cursos">
            <option value="SELECIONA EL CURSO AL CUAL DESEA SUBIR SUS NOTAS"  disabled selected>
                SELECCIONA EL CURSO AL CUAL DESEA SUBIR SUS NOTAS   
            </option>
            <option value="java">JAVA POO</option>
            <option value="backend developer">BACKEND DEVELOPER</option>
            <option value="diseño web">DISEÑO WEB (HTML , CSS ,JAVASCRIPT)</option>

    </select>
            <label>ID del Alumno:</label>
            <input type="text" name="id_alumno" required>

            <label>Nombre Completo:</label>
            <input type="text" name="nombre" required>

            <label>Nota 1:</label>
            <input type="number" name="nota1" step="0.1" min="0" required>

            <label>Nota 2:</label>
            <input type="number" name="nota2" step="0.1" min="0" required>

            <label>Nota 3:</label>
            <input type="number" name="nota3" step="0.1" min="0" required>

            <label>Nota 4:</label>
            <input type="number" name="nota4" step="0.1" min="0" required>

            <button type="submit" name="enviar">Enviar</button>
        </form>

        <?php if (isset($_REQUEST["enviar"])){?>
            <script>
                Swal.fire({
                    title: "QUERIDO ALUMNO",
                    text: "SE HAN REGISTRADO TUS NOTAS",
                    icon: "success"
                });
            </script>
        <?php }; ?>
    </div>


        <button type="submit" class="logout-button"><a href="../close_sesion.php">CERRAR SESION</a></button>

</body>
</html>






