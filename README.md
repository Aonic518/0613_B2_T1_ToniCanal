# Biblioteca Digital

Projecte desenvolupat en PHP orientat a objectes per gestionar una biblioteca digital. Permet administrar materials, usuaris, préstecs i reserves mitjançant una aplicació web.

## Requisits

- PHP 8.0 o superior.
- Composer.
- Un navegador web.

## Instal·lació i execució

1. Descarregar o clonar el projecte.
2. Obrir un terminal a la carpeta principal del projecte.
3. Instal·lar les dependències i generar l'autoload amb Composer:

```bash
composer install
composer dump-autoload
```

4. Iniciar el servidor de desenvolupament de PHP:

```bash
php -S localhost:8000
```

5. Obrir el navegador i accedir a:

```text
http://localhost:8000
```

## Funcionalitats

L'aplicació permet:

- Consultar i cercar llibres, revistes i DVD.
- Veure la informació detallada de cada material.
- Gestionar els usuaris de la biblioteca.
- Realitzar i retornar préstecs.
- Reservar materials disponibles.
- Consultar els préstecs actius i les reserves.
- Consultar estadístiques de la biblioteca.
- Consultar l'historial d'accions.

També incorpora demostracions de conceptes de programació orientada a objectes, com ara els mètodes màgics, la serialització, les excepcions personalitzades i l'autoload de Composer.

## Estructura del projecte

```text
biblioteca/
│
├── src/
│   ├── Data/           # Generació de dades inicials
│   ├── Exceptions/     # Excepcions personalitzades
│   ├── Interfaces/     # Interfícies
│   ├── Models/         # Classes principals
│   └── Traits/         # Traits
│
├── views/              # Pàgines de l'aplicació
├── vendor/             # Dependències de Composer
├── composer.json       # Configuració de Composer
├── index.php           # Punt d'entrada i encaminador
└── README.md           # Documentació
```

## Tecnologies i conceptes utilitzats

- PHP 8 i programació orientada a objectes.
- Herència i classes abstractes.
- Interfícies i traits.
- Encapsulació i polimorfisme.
- Excepcions personalitzades.
- Mètodes màgics de PHP.
- Serialització d'objectes.
- Autoload amb Composer i estàndard PSR-4.
- Sessions PHP per conservar les dades durant la navegació.

## Dades de prova

El projecte incorpora un generador de dades inicials amb diferents materials, usuaris, préstecs i reserves per facilitar les proves de l'aplicació.

Les dades es conserven mitjançant sessions PHP. Es poden reiniciar utilitzant l'opció corresponent de l'aplicació.