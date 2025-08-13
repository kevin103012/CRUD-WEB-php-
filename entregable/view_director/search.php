<?php
include_once "../model/db/dbconnect.php";
if (isset($_POST['nombre_curso'])) {
    $curso = $_POST['nombre_curso'];
    

    //la conexion a la db.
     
    // la query (NO TOCAR SI NO SABES MANEJAR , AVISADO ESTAS COMPARE) 
    $sql = "SELECT 
                n.id_alumno,
                a.nombre_alumno,
                n.promedio,
                c.nombre_curso,
                i.nombre_instructor
            FROM notas n
            JOIN alumnos a ON n.id_alumno = a.id_alumno
            JOIN curso c ON n.id_curso = c.id_curso
            JOIN instructor i ON n.id_instructor = i.id_instructor
            WHERE c.nombre_curso = :curso"; //aca cambiara dependiendo el curso obio :v

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':curso', $curso, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $estado = ($row["promedio"] >= 10.5) ? "APROBADO" : "DESAPROBADO";
        //estilos papeto lendo?>//
        <tr>
            <style>
                a { 
    text-decoration: none;
    font-family: 'Courier New', Courier, monospace;
    font-size: 15px;
    color:#6b3434;
    padding: 5px;
}
            </style>
            <td><?=htmlspecialchars($row["id_alumno"]); ?></td>
            <td><?=htmlspecialchars($row["nombre_alumno"]) ?></td>
            <td><?=htmlspecialchars($row["promedio"]) ?></td>
            <td><?=htmlspecialchars($row["nombre_curso"]) ?></td>
            <td><?=htmlspecialchars($row["nombre_instructor"]) ?></td>
            <td><?=htmlspecialchars( $estado) ?></td>
            <td><a href="#" class="editar"
   data-id="<?= $row["id_alumno"] ?>"
   data-nombre="<?= $row["nombre_alumno"] ?>"
   data-promedio="<?= $row["promedio"] ?>"
   data-curso="<?= $row["nombre_curso"] ?>"
   data-instructor="<?= $row["nombre_instructor"] ?>">
   EDITAR
</a>

 /
    <a href="functions/eliminar.php?id=<?= htmlspecialchars($row["id_alumno"]) ?>" onclick="return confirm('¿Estás seguro de eliminar este alumno?, (se elimina el registro en notas :u)')">ELIMINAR</a></td>
        </tr>
        <?php
    }
} else {
    echo "JAAA no te salio por sano xdd";
}
?>
