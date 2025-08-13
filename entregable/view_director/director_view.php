<?php
session_start();
if (!isset($_SESSION['director_login'])) {
    header("location: ../index.php");
}
if (isset($_SESSION['alumno_login'])) {
    header("location: ../view_alumno/alumno_view.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PANEL Director</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>PANEL DEL DIRECTOR</h1>
<p>Bienvenido: <strong><?= $_SESSION['director_login']; ?></strong></p>

<div class="panel_content">
    <div class="panel_head">
        <label>ELIJA UN CURSO A REVISAR</label>
        <select name="curso" id="curso" class="cursos">
            <option value="" disabled selected>Seleccione un curso</option>
            <option value="backend developer">BACKEND DEVELOPER</option>
            <option value="diseño web">DISEÑO WEB (HTML, CSS, JS)</option>
            <option value="java">JAVA POO</option>
        </select>
    </div>

    <div class="table_content">
        <table class="table_data" border="1">
            <thead>
                <tr>
                    <th>ID ALUMNO</th>
                    <th>NOMBRE ALUMNO</th>
                    <th>PROMEDIO</th>
                    <th>NOMBRE CURSO</th>
                    <th>NOMBRE INSTRUCTOR</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody id="resultado"></tbody>
        </table>
    </div>
</div>

<!-- FORMULARIO EDIT -->
<div id="edit_container" style="display: none;" class="edit_container_xd">
    <form id="form_editar" method="post">
        <label>ID:</label>
        <input type="text" class="inp_new_ctn" name="nw_id_alumno" id="input_id" readonly><br>
        <label>NOMBRE:</label>
        <input type="text" class="inp_new_ctn" name="nw_nombre_alumno" id="input_nombre"><br>
        <label>PROMEDIO:</label>
        <input type="text" class="inp_new_ctn" name="nw_promedio" id="input_promedio"><br>
        <label>CURSO:</label>
        <input type="text" class="inp_new_ctn" name="nw_curso_nombre" id="input_curso"><br>
        <label>INSTRUCTOR:</label>
        <input type="text" class="inp_new_ctn" name="nw_instructor_nombre" id="input_instructor"><br><br>
        <input type="button" class="btn_edit" value="ACTUALIZAR" onclick="enviarFormulario()">
        <input type="button" class="btn_edit" value="CANCELAR" onclick="cerrarFormulario()">
    </form>
</div>

<div class="content_cs">
    <a href="../close_sesion.php"><button class="btn_cs">Cerrar Sesión</button></a>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("curso").addEventListener("change", function () {
        const curso = this.value;

        if (curso) {
            fetch("search.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "nombre_curso=" + encodeURIComponent(curso)
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById("resultado").innerHTML = data;
                agregarEventosEditar(); // Activar eventos en los botones EDITAR
            })
            .catch(err => console.error("Error en el fetch:", err));
        }
    });
});

function agregarEventosEditar() {
    document.querySelectorAll('.editar').forEach(btn => {
        btn.addEventListener('click', function (event) {
            event.preventDefault();

            document.getElementById("input_id").value = this.dataset.id;
            document.getElementById("input_nombre").value = this.dataset.nombre;
            document.getElementById("input_promedio").value = this.dataset.promedio;
            document.getElementById("input_curso").value = this.dataset.curso;
            document.getElementById("input_instructor").value = this.dataset.instructor;

            document.getElementById("edit_container").style.display = "block";
        });
    });
}

function cerrarFormulario() {
    document.getElementById("edit_container").style.display = "none";
}

function enviarFormulario() {
    const form = document.getElementById("form_editar");
    const datos = new FormData(form);

    fetch("editar.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(respuesta => {
        console.log("Respuesta:", respuesta);
        cerrarFormulario();

        const curso = document.getElementById("curso").value;
        if (curso) {
            fetch("search.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "nombre_curso=" + encodeURIComponent(curso)
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById("resultado").innerHTML = data;
                agregarEventosEditar(); // Reactivar eventos
            });
        }
    })
    .catch(err => console.error("Error al actualizar:", err));
}
</script>

</body>
</html>
