<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar_sugerencia"])) {
    $nombre = validarDatos($_POST["nombre"]);
    $comentario = validarDatos($_POST["comentario"]);

    
    $sql = "INSERT INTO buzon (nombre, comentario) VALUES (:nombre, :comentario)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':comentario', $comentario);
    $stmt->execute();
    header("Location:buzon");
}

function validarDatos($datos) {
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Sugerencias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-center"><a href="index" style="text-decoration: none; color: black;">Buzón de Sugerencias</a></h1>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="comentario">Comentario:</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="enviar_sugerencia">Enviar Sugerencia</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
