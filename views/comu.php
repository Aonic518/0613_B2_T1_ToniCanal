<?php
use App\Models\Material;
use App\Models\Usuari;
use App\Models\Llibre;
use App\Models\Revista;
use App\Models\DVD;
use App\Interfaces\Reservable;

function h($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

$estadistiques = $biblioteca->obtenirEstadistiques();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f4f4;
            color: #222;
        }
        nav {
            background: #263849;
            padding: 15px;
            margin-bottom: 25px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 8px;
        }
        nav a:hover { text-decoration: underline; }
        .caixa {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th { background: #e8edf2; }
        .botons a {
            display: inline-block;
            margin: 4px;
            padding: 7px 10px;
            background: #263849;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .error { background: #ffe0e0; padding: 15px; }
        .ok { background: #dff5df; padding: 15px; }
        .targetes {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .targeta {
            background: white;
            padding: 20px;
            border-radius: 8px;
            min-width: 130px;
        }
        pre { white-space: pre-wrap; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">🏠 Inici</a>
    <a href="index.php?page=materials">📚 Materials</a>
    <a href="index.php?page=usuaris">👥 Usuaris</a>
    <a href="index.php?page=prestecs">🔄 Préstecs</a>
    <a href="index.php?page=reserves">📝 Reserves</a>
    <a href="index.php?page=estadistiques">📊 Estadístiques</a>
    <a href="index.php?page=auditoria">🗂️ Auditoria</a>
    <a href="index.php?page=reset">🔄 Reiniciar</a>
</nav>

<h1>Biblioteca Digital</h1>

<?php if ($missatge): ?>
    <p class="ok"><?= h($missatge) ?></p>
<?php endif; ?>

<?php if ($page === "inici"): ?>

    <div class="caixa">
        <h2>Benvingut/da a la biblioteca</h2>
        <p>Panell principal del sistema de gestió.</p>
    </div>

    <div class="targetes">
        <?php foreach ([
            "Total materials" => $estadistiques["total"],
            "Disponibles" => $estadistiques["disponibles"],
            "Prestats" => $estadistiques["prestats"],
            "Reservats" => $estadistiques["reservats"]
        ] as $nom => $valor): ?>
            <div class="targeta">
                <h3><?= h($nom) ?></h3>
                <strong><?= h($valor) ?></strong>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="caixa">
        <h2>Accés ràpid</h2>
        <p><a href="index.php?page=demo_magic">Demo mètodes màgics</a></p>
        <p><a href="index.php?page=demo_serialization">Demo serialització</a></p>
        <p><a href="index.php?page=demo_exceptions">Demo excepcions</a></p>
        <p><a href="index.php?page=demo_autoload">Demo autoload</a></p>
    </div>

<?php elseif ($page === "materials"): ?>

    <h2>Llistat de materials</h2>

    <p class="botons">
        <a href="index.php?page=materials">Tots</a>
        <a href="index.php?page=materials&type=llibre">Llibres</a>
        <a href="index.php?page=materials&type=revista">Revistes</a>
        <a href="index.php?page=materials&type=dvd">DVDs</a>
    </p>

    <?php
    $tipus = match ($_GET["type"] ?? "") {
        "llibre" => "Llibre",
        "revista" => "Revista",
        "dvd" => "DVD",
        default => null
    };

    $materials = $tipus
        ? $biblioteca->llistarPerTipus($tipus)
        : $biblioteca->getMaterials();
    ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Títol</th>
            <th>Autor</th>
            <th>Tipus</th>
            <th>Estat</th>
            <th>Accions</th>
        </tr>
        <?php foreach ($materials as $m): ?>
            <tr>
                <td><?= h($m->getId()) ?></td>
                <td><?= h($m->getTitol()) ?></td>
                <td><?= h($m->getAutor()) ?></td>
                <td><?= h($m->getTipus()) ?></td>
                <td><?= $m->isDisponible() ? "Disponible" : "Prestat" ?></td>
                <td>
                    <a href="index.php?page=material&id=<?= h($m->getId()) ?>">Detall</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php elseif ($page === "material"): ?>

    <?php $m = $biblioteca->cercarPerId((int)($_GET["id"] ?? 0)); ?>

    <?php if ($m): ?>
        <div class="caixa">
            <h2><?= h($m->getTitol()) ?></h2>
            <p>ID: <?= h($m->getId()) ?></p>
            <p>Autor: <?= h($m->getAutor()) ?></p>
            <p>Any: <?= h($m->getAnyPublicacio()) ?></p>
            <p>Tipus: <?= h($m->getTipus()) ?></p>
            <p>Estat: <?= $m->isDisponible() ? "Disponible" : "Prestat" ?></p>

            <?php if ($m instanceof Llibre): ?>
                <p>Pàgines: <?= h($m->getNumeroPagines()) ?></p>
            <?php elseif ($m instanceof Revista): ?>
                <p>Edició: <?= h($m->getNumeroEdicio()) ?></p>
            <?php elseif ($m instanceof DVD): ?>
                <p>Duració: <?= h($m->getDuracioFormatada()) ?></p>
            <?php endif; ?>

            <?php if ($m instanceof Reservable): ?>
                <p>Reservat: <?= $m->estaReservat() ? "Sí" : "No" ?></p>
                <?php if ($m->estaReservat()): ?>
                    <p>Usuari reserva: <?= h($m->getUsuariReserva()) ?></p>
                <?php endif; ?>
            <?php endif; ?>

            <h3>Historial d'auditoria</h3>
            <ul>
                <?php foreach ($m->obtenirHistorial() as $accio): ?>
                    <li>
                        <?= h($accio["data"]) ?> -
                        <?= h($accio["accio"]) ?> -
                        <?= h($accio["detalls"]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p>Material no trobat.</p>
    <?php endif; ?>

<?php elseif ($page === "usuaris"): ?>

    <h2>Llistat d'usuaris</h2>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Accions</th></tr>
        <?php foreach ($biblioteca->getUsuaris() as $u): ?>
            <tr>
                <td><?= h($u->getId()) ?></td>
                <td><?= h($u->getNom()) ?></td>
                <td><?= h($u->getEmail()) ?></td>
                <td>
                    <a href="index.php?page=usuari&id=<?= h($u->getId()) ?>">Perfil</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php elseif ($page === "usuari"): ?>

    <?php $u = $biblioteca->cercarUsuari((int)($_GET["id"] ?? 0)); ?>

    <?php if ($u): ?>
        <h2>Perfil de <?= h($u->getNom()) ?></h2>
        <p>Email: <?= h($u->getEmail()) ?></p>
        <p>Data de registre: <?= h($u->getDataRegistre()->format("d/m/Y")) ?></p>
        <p>Préstecs actius: <?= h($u->getNumeroMaterialsPrestats()) ?></p>

        <h3>Préstecs de l'usuari</h3>
        <ul>
            <?php foreach ($biblioteca->llistarPrestecsUsuari($u->getId()) as $p): ?>
                <li>
                    <?= h($p->getMaterial()->getTitol()) ?> -
                    <?= $p->estaActiu() ? "Actiu" : "Retornat" ?>
                    <?php if ($p->estaActiu()): ?>
                        - <a href="index.php?page=retorn&material_id=<?= h($p->getMaterial()->getId()) ?>">Retornar</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3>Notificacions</h3>
        <ul>
            <?php foreach ($u->obtenirNotificacions() as $n): ?>
                <li><?= h($n) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Usuari no trobat.</p>
    <?php endif; ?>

<?php elseif ($page === "prestecs"): ?>

    <h2>Préstecs actius</h2>
    <table>
        <tr><th>Material</th><th>Usuari</th><th>Data</th><th>Dies pendents</th><th>Multa</th><th>Acció</th></tr>
        <?php foreach ($biblioteca->llistarPrestecsActius() as $p): ?>
            <tr>
                <td><?= h($p->getMaterial()->getTitol()) ?></td>
                <td><?= h($p->getUsuari()->getNom()) ?></td>
                <td><?= h($p->getDataPrestec()->format("d/m/Y")) ?></td>
                <td><?= h($p->getDiesPendents()) ?></td>
                <td><?= number_format($p->calcularMulta(), 2) ?> €</td>
                <td>
                    <a href="index.php?page=retorn&material_id=<?= h($p->getMaterial()->getId()) ?>">Retornar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Fer un préstec</h3>
    <p>Tria un material disponible i un usuari:</p>
    <table>
        <tr><th>Material</th><th>Usuari</th><th>Acció</th></tr>
        <?php foreach ($biblioteca->llistarDisponibles() as $m): ?>
            <?php foreach ($biblioteca->getUsuaris() as $u): ?>
                <tr>
                    <td><?= h($m->getTitol()) ?></td>
                    <td><?= h($u->getNom()) ?></td>
                    <td>
                        <a href="index.php?page=prestec&material_id=<?= h($m->getId()) ?>&usuari_id=<?= h($u->getId()) ?>">Prestar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>

<?php elseif ($page === "reserves"): ?>

    <h2>Reserves actives</h2>
    <ul>
        <?php foreach ($biblioteca->llistarReserves() as $m): ?>
            <li>
                <?= h($m->getTitol()) ?> -
                <?= h($m->getUsuariReserva()) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Crear una reserva</h3>
    <table>
        <tr><th>Material</th><th>Usuari</th><th>Acció</th></tr>
        <?php foreach ($biblioteca->llistarDisponibles() as $m): ?>
            <?php if ($m instanceof Reservable && !$m->estaReservat()): ?>
                <?php foreach ($biblioteca->getUsuaris() as $u): ?>
                    <tr>
                        <td><?= h($m->getTitol()) ?></td>
                        <td><?= h($u->getNom()) ?></td>
                        <td>
                            <a href="index.php?page=reserva&material_id=<?= h($m->getId()) ?>&usuari_id=<?= h($u->getId()) ?>">Reservar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>

<?php elseif ($page === "estadistiques"): ?>

    <h2>Estadístiques</h2>
    <div class="targetes">
        <?php foreach ($estadistiques as $nom => $valor): ?>
            <?php if (!is_array($valor)): ?>
                <div class="targeta">
                    <h3><?= h($nom) ?></h3>
                    <strong><?= h($valor) ?></strong>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h3>Materials per tipus</h3>
    <ul>
        <?php foreach ($estadistiques["perTipus"] as $tipus => $total): ?>
            <li><?= h($tipus) ?>: <?= h($total) ?></li>
        <?php endforeach; ?>
    </ul>

<?php elseif ($page === "auditoria"): ?>

    <h2>Historial d'auditoria</h2>
    <?php foreach ($biblioteca->getMaterials() as $m): ?>
        <h3><?= h($m->getTitol()) ?></h3>
        <ul>
            <?php foreach ($m->obtenirHistorial() as $accio): ?>
                <li>
                    <?= h($accio["data"]) ?> -
                    <?= h($accio["accio"]) ?> -
                    <?= h($accio["detalls"]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

<?php elseif ($page === "demo_magic"): ?>

    <h2>Demo de mètodes màgics</h2>
    <?php $m = $biblioteca->cercarPerId(1); ?>
    <p><strong>__toString():</strong> <?= h($m) ?></p>
    <p><strong>__call() getLlibres():</strong> <?= count($biblioteca->getLlibres()) ?> llibres</p>
    <?php $u = $biblioteca->cercarUsuari(1); ?>
    <p><strong>__get():</strong> <?= h($u->nom) ?></p>
    <?php $u->email = "nou@mail.com"; ?>
    <p><strong>__set():</strong> <?= h($u->email) ?></p>
    <?php
    try {
        $u->nom = "Canvi no permès";
    } catch (\LogicException $e) {
        echo "<p>__set() no permès: " . h($e->getMessage()) . "</p>";
    }
    ?>

<?php elseif ($page === "demo_serialization"): ?>

    <h2>Demo de serialització</h2>
    <?php
    $u = $biblioteca->cercarUsuari(1);
    $u->afegirNotificacio("Aquesta notificació no es guardarà.");
    $serialitzat = serialize($u);
    $recuperat = unserialize($serialitzat);
    ?>
    <p>Objecte serialitzat:</p>
    <pre><?= h($serialitzat) ?></pre>
    <p>Usuari recuperat: <?= h($recuperat) ?></p>
    <p>Notificacions després de recuperar: <?= count($recuperat->obtenirNotificacions()) ?></p>

<?php elseif ($page === "demo_exceptions"): ?>

    <h2>Demo d'excepcions</h2>
    <?php
    $proves = [
        "MaterialNoTrobatException" => function () use ($biblioteca) {
            $biblioteca->prestarMaterial(999, 1);
        },
        "UsuariNoTrobatException" => function () use ($biblioteca) {
            $biblioteca->prestarMaterial(3, 999);
        },
        "MaterialNoDisponibleException" => function () use ($biblioteca) {
            $biblioteca->reservarMaterial(5, 1);
        },
        "MaterialNoPrestatException" => function () use ($biblioteca) {
            $biblioteca->retornarMaterial(3);
        },
        "EmailInvalidException" => function () {
            new Usuari(99, "Prova", "email-mal-format");
        }
    ];

    foreach ($proves as $nom => $prova) {
        echo "<div class='caixa'><strong>" . h($nom) . ":</strong> ";
        try {
            $prova();
            echo "No s'ha produït cap excepció.";
        } catch (\Throwable $e) {
            echo h($e->getMessage());
        }
        echo "</div>";
    }
    ?>

<?php elseif ($page === "demo_autoload"): ?>

    <h2>Demo autoload</h2>
    <p>Classes de l'aplicació carregades:</p>
    <ul>
        <?php foreach (get_declared_classes() as $classe): ?>
            <?php if (str_starts_with($classe, "App\\")): ?>
                <li><?= h($classe) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

<?php else: ?>

    <h2>Pàgina no trobada</h2>
    <p>La pàgina que busques no existeix.</p>

<?php endif; ?>

</body>
</html>