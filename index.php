<?php

require_once __DIR__ . "/vendor/autoload.php";

use App\Data\DataGenerator;
use App\Exceptions\MaterialNoDisponibleException;

session_start();

// Reiniciar les dades.
if (($_GET["page"] ?? "") === "reset") {
    $_SESSION = [];
    session_destroy();

    header("Location: index.php");
    exit;
}

// Crear la biblioteca només la primera vegada.
if (!isset($_SESSION["biblioteca"])) {
    $_SESSION["biblioteca"] = DataGenerator::crear();
}

$biblioteca = $_SESSION["biblioteca"];

$page = $_GET["page"] ?? "inici";
$error = null;
$missatge = null;

try {
    // Accions de préstec, retorn i reserva.
    if ($page === "prestec") {
        $biblioteca->prestarMaterial(
            (int)($_GET["material_id"] ?? 0),
            (int)($_GET["usuari_id"] ?? 0)
        );

        $_SESSION["biblioteca"] = $biblioteca;

        header("Location: index.php?page=usuari&id=" . (int)$_GET["usuari_id"]);
        exit;
    }

    if ($page === "retorn") {
        $multa = $biblioteca->retornarMaterial(
            (int)($_GET["material_id"] ?? 0)
        );

        $_SESSION["biblioteca"] = $biblioteca;

        $missatge = "Material retornat. Multa: " . number_format($multa, 2) . " €";
        $page = "inici";
    }

    if ($page === "reserva") {
        $biblioteca->reservarMaterial(
            (int)($_GET["material_id"] ?? 0),
            (int)($_GET["usuari_id"] ?? 0)
        );

        $_SESSION["biblioteca"] = $biblioteca;

        $missatge = "Reserva realitzada correctament.";
        $page = "reserves";
    }

    $vistes = [
        "inici" => "inici.php",
        "materials" => "materials.php",
        "material" => "material.php",
        "usuaris" => "usuaris.php",
        "usuari" => "usuari.php",
        "prestecs" => "prestecs.php",
        "reserves" => "reserves.php",
        "estadistiques" => "estadistiques.php",
        "auditoria" => "auditoria.php",
        "demo_magic" => "demo_magic.php",
        "demo_serialization" => "demo_serialization.php",
        "demo_exceptions" => "demo_exceptions.php",
        "demo_autoload" => "demo_autoload.php"
    ];

    $fitxer = $vistes[$page] ?? "404.php";

    require __DIR__ . "/views/" . $fitxer;

} catch (MaterialNoDisponibleException $e) {
    $error = (string)$e;
    require __DIR__ . "/views/error.php";

} catch (\Throwable $e) {
    $error = $e->getMessage();
    require __DIR__ . "/views/error.php";
}

// Guardar sempre els canvis a la sessió.
$_SESSION["biblioteca"] = $biblioteca;