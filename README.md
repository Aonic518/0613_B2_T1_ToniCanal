# Sistema de Gestió de Biblioteca Digital

**Mòdul:** M0613-B2 - PHP orientat a objectes  
**Autor:** Toni Canal  
**Llenguatge:** PHP 8+  
**Gestor de dependències:** Composer

## 1. Descripció del projecte

Aquest projecte consisteix en el desenvolupament d'un sistema de gestió d'una biblioteca digital mitjançant PHP orientat a objectes.

L'aplicació permet gestionar els materials de la biblioteca, els usuaris, els préstecs i les reserves. També incorpora un sistema d'auditoria, estadístiques i diferents demostracions tècniques dels conceptes de programació orientada a objectes treballats a classe.

Les dades es mantenen mitjançant sessions de PHP, sense necessitat d'utilitzar una base de dades.

## 2. Requisits

Per executar el projecte cal tenir instal·lat:

- PHP 8.0 o superior.
- Composer.
- Un navegador web.

## 3. Instal·lació

1. Clonar el repositori:

```bash
git clone https://github.com/Aonic518/0613_B2_T1_ToniCanal.git
```

2. Entrar a la carpeta del projecte:

```bash
cd 0613_B2_T1_ToniCanal
```

3. Instal·lar les dependències i generar l'autoload:

```bash
composer install
```

4. Iniciar el servidor de desenvolupament de PHP:

```bash
php -S localhost:8000
```

5. Obrir el navegador i accedir a:

```text
http://localhost:8000
```

## 4. URLs i funcionalitats

Totes les pàgines es gestionen des de `index.php`, que actua com a punt d'entrada i router de l'aplicació.

### 4.1. Pàgina principal

| URL | Funcionalitat |
|---|---|
| `index.php` | Mostra la pàgina inicial amb un resum de la biblioteca i l'accés a les diferents seccions. |

### 4.2. Materials

| URL | Funcionalitat |
|---|---|
| `index.php?page=materials` | Mostra tots els materials de la biblioteca. |
| `index.php?page=materials&type=llibre` | Filtra els materials per mostrar només els llibres. |
| `index.php?page=materials&type=revista` | Filtra els materials per mostrar només les revistes. |
| `index.php?page=materials&type=dvd` | Filtra els materials per mostrar només els DVDs. |
| `index.php?page=material&id=3` | Mostra el detall d'un material i el seu historial d'auditoria. |

### 4.3. Usuaris

| URL | Funcionalitat |
|---|---|
| `index.php?page=usuaris` | Mostra el llistat de tots els usuaris. |
| `index.php?page=usuari&id=4` | Mostra el perfil d'un usuari i els seus préstecs. |
| `index.php?page=usuari&id=4&action=prestecs` | Mostra l'historial de préstecs de l'usuari. |

### 4.4. Préstecs

| URL | Funcionalitat |
|---|---|
| `index.php?page=prestecs` | Mostra els préstecs actius i permet identificar els que estan vençuts. |
| `index.php?page=prestec&material_id=3&usuari_id=4` | Realitza un préstec d'un material a un usuari. |
| `index.php?page=retorn&material_id=1` | Retorna un material i mostra la multa corresponent, si n'hi ha. |

### 4.5. Reserves

| URL | Funcionalitat |
|---|---|
| `index.php?page=reserves` | Mostra les reserves actives. |
| `index.php?page=reserva&material_id=9&usuari_id=4` | Crea una reserva d'un material per a un usuari. |

Les reserves només estan disponibles per als llibres i els DVDs. Les revistes no es poden reservar.

### 4.6. Estadístiques i auditoria

| URL | Funcionalitat |
|---|---|
| `index.php?page=estadistiques` | Mostra les estadístiques generals de la biblioteca: materials, disponibilitat, préstecs, reserves i multes. |
| `index.php?page=auditoria` | Mostra l'historial d'accions registrades pels materials. |

### 4.7. Demostracions tècniques

| URL | Funcionalitat |
|---|---|
| `index.php?page=demo_magic` | Demostra els mètodes màgics `__toString()`, `__call()`, `__get()` i `__set()`. |
| `index.php?page=demo_serialization` | Demostra la serialització d'un usuari amb `serialize()`, `__sleep()` i `__wakeup()`. |
| `index.php?page=demo_exceptions` | Provoca i captura les cinc excepcions personalitzades del projecte. |
| `index.php?page=demo_autoload` | Mostra les classes carregades mitjançant l'autoload de Composer. |
| `index.php?page=reset` | Reinicia les dades de la sessió i torna a generar les dades inicials. |

## 5. Estructura del projecte

El projecte està organitzat en diferents carpetes per separar les classes de la lògica de l'aplicació i les vistes:

```text
biblioteca/
│
├── composer.json
├── composer.lock
├── .gitignore
├── README.md
├── index.php
│
├── views/
│   ├── inici.php
│   ├── materials.php
│   ├── material.php
│   ├── usuaris.php
│   ├── usuari.php
│   ├── prestecs.php
│   ├── reserves.php
│   ├── estadistiques.php
│   ├── auditoria.php
│   ├── demo_magic.php
│   ├── demo_serialization.php
│   ├── demo_exceptions.php
│   ├── demo_autoload.php
│   └── ...
│
└── src/
    ├── Models/
    │   ├── Material.php
    │   ├── Llibre.php
    │   ├── Revista.php
    │   ├── DVD.php
    │   ├── Usuari.php
    │   ├── Prestec.php
    │   └── Biblioteca.php
    │
    ├── Interfaces/
    │   └── Reservable.php
    │
    ├── Traits/
    │   └── Auditoria.php
    │
    ├── Exceptions/
    │   ├── MaterialNoTrobatException.php
    │   ├── MaterialNoDisponibleException.php
    │   ├── MaterialNoPrestatException.php
    │   ├── UsuariNoTrobatException.php
    │   └── EmailInvalidException.php
    │
    └── Data/
        └── DataGenerator.php
```

## 6. Conceptes de programació orientada a objectes utilitzats

Durant el desenvolupament s'han aplicat els següents conceptes:

### 6.1. Classes abstractes i herència

- `Material` és una classe abstracta que defineix les característiques comunes dels materials.
- `Llibre`, `Revista` i `DVD` hereten de `Material`.
- Cada classe implementa els seus propis mètodes per calcular les multes i identificar el tipus de material.

### 6.2. Interfícies

- `Reservable` defineix els mètodes necessaris per gestionar les reserves.
- La implementen `Llibre` i `DVD`.
- Les revistes no implementen aquesta interfície perquè no es poden reservar.

### 6.3. Traits

- `Auditoria` permet reutilitzar el codi de registre d'accions.
- Els materials poden registrar accions com ara préstecs, retorns i reserves.

### 6.4. Encapsulació i visibilitat

S'utilitzen propietats `private`, `protected` i `public` per controlar l'accés a les dades.

També s'utilitzen getters i setters per consultar i modificar propietats de manera controlada, aplicant validacions quan és necessari.

### 6.5. Mètodes màgics

S'han treballat els següents mètodes màgics de PHP:

- `__toString()`: permet representar objectes com a cadenes de text.
- `__call()`: permet gestionar crides a mètodes inexistents.
- `__get()`: permet accedir de manera controlada a determinades propietats.
- `__set()`: permet modificar propietats amb validació.
- `__sleep()`: controla quines propietats es guarden durant la serialització.
- `__wakeup()`: reinicialitza les notificacions després de recuperar un objecte serialitzat.

### 6.6. Excepcions personalitzades

S'han creat cinc excepcions pròpies per gestionar els errors de manera controlada:

- `MaterialNoTrobatException`
- `MaterialNoDisponibleException`
- `MaterialNoPrestatException`
- `UsuariNoTrobatException`
- `EmailInvalidException`

Aquestes excepcions permeten mostrar missatges concrets quan es produeixen errors, com intentar prestar un material que no està disponible o retornar un material que no estava prestat.

### 6.7. Namespaces i autoloading

S'utilitzen namespaces per organitzar les classes i evitar conflictes de noms.

Composer gestiona la càrrega automàtica de les classes mitjançant l'estàndard PSR-4.

### 6.8. Característiques de PHP 8

El projecte incorpora diferents característiques de PHP 8:

- Typed properties.
- Constructor property promotion.
- Nullable types.
- Union types.
- Tipus `mixed`.
- Expressions `match`.

## 7. Dades i persistència

Les dades inicials es creen mitjançant la classe `DataGenerator`.

La biblioteca es guarda a la sessió de PHP perquè els canvis realitzats a l'aplicació es mantinguin entre les diferents pàgines.

No s'utilitza cap base de dades.

Les dades inicials inclouen materials, usuaris, préstecs actius, un préstec vençut i una reserva.

L'opció `index.php?page=reset` permet reiniciar la sessió i recuperar les dades inicials.

## 8. Decisions de disseny i millores

Algunes de les decisions preses durant el desenvolupament són:

- Separar les classes de model, les interfícies, els traits, les excepcions i les dades en carpetes diferents.
- Utilitzar Composer i PSR-4 per evitar haver de carregar manualment cada classe.
- Fer servir una classe abstracta `Material` per evitar repetir propietats i mètodes comuns.
- Utilitzar la interfície `Reservable` només en els materials que permeten reserves.
- Utilitzar el trait `Auditoria` per centralitzar el registre d'accions.
- Guardar la biblioteca a la sessió per mantenir les dades sense necessitat d'una base de dades.
- Utilitzar excepcions personalitzades per controlar els errors i mostrar missatges comprensibles.
- Centralitzar la navegació en un únic fitxer `index.php` mitjançant un router amb `match`.

## 9. Vídeo de demostració

**Enllaç al vídeo:** (https://youtu.be/sjn5Tjpz8Hc)

El vídeo mostra el funcionament de l'aplicació, incloent-hi la gestió de materials, els préstecs, les excepcions, les reserves, les estadístiques, l'auditoria i les demostracions dels mètodes màgics.

## 10. Repositori

**GitHub:** https://github.com/Aonic518/0613_B2_T1_ToniCanal.git