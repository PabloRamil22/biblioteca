<?php include('conexion.php'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Autores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/autor.css">
</head>
<body>
    <div class="container mt-5">
        <h2><a href="index" style="text-decoration: none; color: black;">Gestión de Autores</a></h2>

        <?php
        // Agregar nuevo autor
        if (isset($_POST['guardar_autor'])) {
            $nombre = $_POST['nombre'];
            $sql = 'INSERT INTO autores (nombre) VALUES (:nombre)';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['nombre' => $nombre]);
            header("Location: autor.php");
        }

        // Actualizar autor existente
        if (isset($_POST['actualizar_autor'])) {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $sql = 'UPDATE autores SET nombre = :nombre WHERE idautores = :id';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['nombre' => $nombre, 'id' => $id]);
            echo "<div class='alert alert-success mt-3'>Autor actualizado exitosamente</div>";
        }

        // Eliminar autor(Solo se puede eliminar un autor si no hay libros asociados a ese autor)
        if (isset($_GET['eliminar'])) {
            $id = $_GET['eliminar'];

            // Verificar si hay libros asociados al autor
            $sql_libros = 'SELECT COUNT(*) FROM libros WHERE autor = :id';
            $stmt_libros = $conn->prepare($sql_libros);
            $stmt_libros->execute(['id' => $id]);
            $num_libros = $stmt_libros->fetchColumn();

            if ($num_libros > 0) {
                echo "<div class='alert alert-danger mt-3'>No se puede eliminar el autor porque tiene libros asociados. Elimina los libros primero.</div>";
            } else {
                
                $sql_eliminar_autor = 'DELETE FROM autores WHERE idautores = :id';
                $stmt_eliminar_autor = $conn->prepare($sql_eliminar_autor);
                $stmt_eliminar_autor->execute(['id' => $id]);
                echo "<div class='alert alert-success mt-3'>Autor eliminado exitosamente</div>";
            }
        }

        // Obtener autor para editar
        if (isset($_GET['editar'])) {
            $id = $_GET['editar'];
            $sql = 'SELECT * FROM autores WHERE idautores = :id';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $autor = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>

        <form action="autor.php" method="POST" class="mb-4">
            <input type="hidden" name="id" value="<?php echo isset($autor['idautores']) ? $autor['idautores'] : ''; ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombre">Nombre del autor:</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo isset($autor['nombre']) ? $autor['nombre'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-6 align-self-end">
                    <div class="form-row">
                        <div class="col-auto">
                            <?php if (isset($autor['idautores'])) : ?>
                                <button type="submit" name="actualizar_autor" class="btn btn-primary">Actualizar</button>
                                <a href="autor.php" class="btn btn-secondary ml-2">Cancelar</a>
                            <?php else : ?>
                                <button type="submit" name="guardar_autor" class="btn btn-success">Añadir</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <h2>Lista de Autores</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query('SELECT * FROM autores');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                    <td>{$row['idautores']}</td>
                    <td>{$row['nombre']}</td>
                    <td>
                        <a href='autor.php?editar={$row['idautores']}' class='btn btn-warning'>Editar</a>
                        <a href='autor.php?eliminar={$row['idautores']}' class='btn btn-danger'>Eliminar</a>
                    </td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
