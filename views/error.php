<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Error - Biblioteca Digital</title>
</head>
<body>
    <h1>S'ha produït un error</h1>

    <p style="color:red">
        <?= htmlspecialchars($error ?? "Error desconegut", ENT_QUOTES, "UTF-8") ?>
    </p>

    <a href="index.php">Tornar a l'inici</a>
</body>
</html>