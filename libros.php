<?php

require_once("conexion.php");


function validarDatos($datos)
{
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}

// Agregar un nuevo libro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_libro"])) {
    $titulo = validarDatos($_POST["titulo"]);
    $autor = validarDatos($_POST["autor"]);

    
    $sql = "INSERT INTO libros (titulo, autor) VALUES (:titulo, :autor)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->execute();
    header("Location: libros");
}

// Eliminar un libro
if (isset($_GET["eliminar"]) && !empty($_GET["eliminar"])) {
    $id_libro_eliminar = $_GET["eliminar"];

    
    $sql = "DELETE FROM libros WHERE idlibros = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_libro_eliminar);
    $stmt->execute();
}

// Consulta para obtener la lista de libros con el nombre de su autor
$sql = "SELECT libros.idlibros, libros.titulo AS titulo_libro, autores.nombre AS nombre_autor 
        FROM libros 
        INNER JOIN autores ON libros.autor = autores.idautores";
$stmt = $conn->query($sql);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/libros.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4"><a href="index" style="text-decoration: none; color: black;">Lista de Libros</a></h2>

    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="titulo">Título del Libro</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="form-group col-md-4">
                <label for="autor">Autor</label>
                <select id="autor" class="form-control" name="autor" required>
                    <option selected disabled>Seleccionar Autor</option>
                    <?php
                    
                    $sql_autores = "SELECT * FROM autores";
                    $stmt_autores = $conn->query($sql_autores);
                    $autores = $stmt_autores->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($autores as $autor) {
                        echo "<option value='" . $autor['idautores'] . "'>" . $autor['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-2 align-self-end mt-auto">
                <button type="submit" class="btn btn-biblioteca btn-block" name="agregar_libro">Agregar Libro</button>
            </div>
        </div>
    </form>

    
    <table class="table table-striped table-biblioteca">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Título del Libro</th>
                <th>Autor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($libros as $libro): ?>
                <tr>
                    <td><?php echo $libro['idlibros']; ?></td>
                    <td><?php echo $libro['titulo_libro']; ?></td>
                    <td><?php echo $libro['nombre_autor']; ?></td>
                    <td>
                        <a href="editar_libro.php?id=<?php echo $libro['idlibros']; ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?eliminar=<?php echo $libro['idlibros']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
