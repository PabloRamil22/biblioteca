<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">Biblioteca Municipal de Redondela</h1>
            <p class="lead">Elevación de la conciencia</p>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <a href="autor" class="btn btn-primary btn-lg btn-block">Autores</a>
                </div>
                <div class="col-md-6">
                    <a href="libros" class="btn btn-success btn-lg btn-block">Libros</a>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <p>© <?php echo date("Y"); ?> Biblioteca Municipal de Redondela. </p>
            </p>
        </div>
    </footer>
    <span class="sugerencias-icon" onclick="window.location.href='buzon.php'" title="Buzón de Sugerencias">&#128233;</span>

</body>
</html>
