<?php
// Conexión a la base de datos
require_once("conexion.php");

// Función para limpiar y validar datos de entrada
function validarDatos($datos) {
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}

// Verificar si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_libro"])) {
    $id_libro = validarDatos($_POST["id_libro"]);
    $titulo = validarDatos($_POST["titulo"]);
    $autor = validarDatos($_POST["autor"]);

    // Actualizar el libro en la base de datos
    $sql = "UPDATE libros SET titulo = :titulo, autor = :autor WHERE idlibros = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':id', $id_libro);
    $stmt->execute();

    // Redireccionar de vuelta a la lista de libros con un mensaje
    header("Location: libros");
    exit();
}

// Obtener el ID del libro a editar desde la URL
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: libros");
    exit();
}
$id_libro_editar = $_GET["id"];

// Consulta para obtener los detalles del libro a editar
$sql = "SELECT * FROM libros WHERE idlibros = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_libro_editar);
$stmt->execute();
$libro = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener la lista de autores
$sql_autores = "SELECT * FROM autores";
$stmt_autores = $conn->query($sql_autores);
$autores = $stmt_autores->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Editar Libro</h2>

    <!-- Formulario para editar el libro -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4">
        <input type="hidden" name="id_libro" value="<?php echo $libro['idlibros']; ?>">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="titulo">Título del Libro</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $libro['titulo']; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="autor">Autor</label>
                <select id="autor" class="form-control" name="autor" required>
                    <option selected disabled>Seleccionar Autor</option>
                    <?php foreach ($autores as $autor): ?>
                        <option value="<?php echo $autor['idautores']; ?>" <?php if ($libro['autor'] == $autor['idautores']) echo "selected"; ?>><?php echo $autor['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-primary" name="editar_libro">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
