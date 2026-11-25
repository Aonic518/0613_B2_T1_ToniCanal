# Biblioteca Digital

Aplicació web desenvolupada amb **PHP 8 i programació orientada a objectes** per gestionar una biblioteca digital. Permet administrar llibres, revistes, DVD, usuaris, préstecs i reserves, així com consultar estadístiques i l'historial d'accions.

## Requisits previs

Per executar el projecte cal tenir instal·lat:

- PHP 8.0 o superior.
- Composer.
- Un navegador web.

## Instal·lació

1. Descarregar o clonar aquest repositori:

```bash
git clone https://github.com/Aonic518/0613_B2_T1_ToniCanal.git
```

2. Entrar a la carpeta del projecte:

```bash
cd 0613_B2_T1_ToniCanal
```

3. Instal·lar les dependències i generar l'autoload de Composer:

```bash
composer install
```

## Execució

Des de la carpeta principal del projecte, executar:

```bash
php -S localhost:8000
```

Obrir el navegador i accedir a:

```text
http://localhost:8000
```

Per aturar el servidor, prémer `Ctrl + C` al terminal.

## URLs i navegació

Totes les pàgines es gestionen des de `index.php`, que actua com a punt d'entrada i encaminador de l'aplicació.

| URL | Descripció |
|---|---|
| `http://localhost:8000/` | Pàgina inicial amb un resum de la biblioteca. |
| `http://localhost:8000/index.php?page=materials` | Mostra tots els materials i permet filtrar-los per tipus. |
| `http://localhost:8000/index.php?page=material&id=3` | Mostra la fitxa detallada d'un material. Es pot canviar l'ID. |
| `http://localhost:8000/index.php?page=usuaris` | Mostra el llistat d'usuaris. |
| `http://localhost:8000/index.php?page=usuari&id=1` | Mostra la informació i els préstecs d'un usuari. Es pot canviar l'ID. |
| `http://localhost:8000/index.php?page=prestecs` | Mostra els préstecs de la biblioteca. |
| `http://localhost:8000/index.php?page=reserves` | Mostra els materials reservats. |
| `http://localhost:8000/index.php?page=estadistiques` | Mostra les estadístiques de la biblioteca. |
| `http://localhost:8000/index.php?page=auditoria` | Mostra l'historial d'accions dels materials. |
| `http://localhost:8000/index.php?page=demo_magic` | Demostració dels mètodes màgics de PHP. |
| `http://localhost:8000/index.php?page=demo_serialization` | Demostració de la serialització i deserialització d'objectes. |
| `http://localhost:8000/index.php?page=demo_exceptions` | Demostració de les excepcions personalitzades. |
| `http://localhost:8000/index.php?page=demo_autoload` | Demostració de l'autoload de Composer. |
| `http://localhost:8000/index.php?page=reset` | Reinicia la sessió i genera de nou les dades inicials. |

Les operacions de préstec, retorn i reserva es realitzen mitjançant els formularis de les pàgines corresponents.

## Funcionalitats principals

### Gestió de materials

- Gestió de llibres, revistes i DVD.
- Consulta de la informació detallada de cada material.
- Cerca i filtratge de materials.
- Control de la disponibilitat.
- Càlcul de multes segons el tipus de material i els dies de retard.

### Gestió d'usuaris

- Consulta dels usuaris registrats.
- Visualització de les dades de cada usuari.
- Consulta de l'historial de préstecs.

### Préstecs i reserves

- Realització de préstecs.
- Retorn de materials.
- Càlcul automàtic de multes per retard.
- Reserva de materials que implementen la interfície `Reservable`.
- Control d'errors quan un material no està disponible o no es pot reservar.

### Estadístiques i auditoria

- Nombre total de materials.
- Materials disponibles, prestats i reservats.
- Recompte de materials per tipus.
- Multes pendents.
- Registre de les accions realitzades sobre els materials.

## Estructura del projecte

```text
Tasca1/
│
├── src/
│   ├── Data/
│   │   └── DataGenerator.php
│   │
│   ├── Exceptions/
│   │   ├── EmailInvalidException.php
│   │   ├── MaterialNoDisponibleException.php
│   │   ├── MaterialNoPrestatException.php
│   │   ├── MaterialNoTrobatException.php
│   │   └── UsuariNoTrobatException.php
│   │
│   ├── Interfaces/
│   │   └── Reservable.php
│   │
│   ├── Models/
│   │   ├── Biblioteca.php
│   │   ├── DVD.php
│   │   ├── Llibre.php
│   │   ├── Material.php
│   │   ├── Prestec.php
│   │   ├── Revista.php
│   │   └── Usuari.php
│   │
│   └── Traits/
│       └── Auditoria.php
│
├── views/
│   ├── auditoria.php
│   ├── demo_autoload.php
│   ├── demo_exceptions.php
│   ├── demo_magic.php
│   ├── demo_serialization.php
│   ├── estadistiques.php
│   ├── inici.php
│   ├── material.php
│   ├── materials.php
│   ├── prestecs.php
│   ├── reserves.php
│   ├── usuari.php
│   ├── usuaris.php
│   └── comu.php
│
├── index.php
├── composer.json
├── composer.lock
├── .gitignore
└── README.md
```

La carpeta `vendor/` es genera automàticament amb `composer install` i no s