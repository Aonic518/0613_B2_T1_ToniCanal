<?php
namespace App\Data;

use App\Models\Biblioteca;
use App\Models\Llibre;
use App\Models\Revista;
use App\Models\DVD;
use App\Models\Usuari;

/**
 * Genera les dades inicials de prova i crea una biblioteca amb materials, usuaris, préstecs i reserves.
 */
class DataGenerator
{
    public static function crear(): Biblioteca
    {
        $biblioteca = new Biblioteca("Biblioteca Digital");

        // 4 llibres.
        $biblioteca->afegirMaterial(
            new Llibre(1, "El nom de la rosa", "Umberto Eco", 1980, 512)
        );

        $biblioteca->afegirMaterial(
            new Llibre(2, "La plaça del Diamant", "Mercè Rodoreda", 1962, 256)
        );

        $biblioteca->afegirMaterial(
            new Llibre(3, "1984", "George Orwell", 1949, 328)
        );

        $biblioteca->afegirMaterial(
            new Llibre(4, "El petit príncep", "Antoine de Saint-Exupéry", 1943, 96)
        );

        // 3 revistes.
        $biblioteca->afegirMaterial(
            new Revista(5, "National Geographic", "Diversos autors", 2024, 120)
        );

        $biblioteca->afegirMaterial(
            new Revista(6, "Muy Interesante", "Diversos autors", 2023, 85)
        );

        $biblioteca->afegirMaterial(
            new Revista(7, "Sàpiens", "Diversos autors", 2024, 240)
        );

        // 3 DVDs.
        $biblioteca->afegirMaterial(
            new DVD(8, "Interestelar", "Christopher Nolan", 2014, 169)
        );

        $biblioteca->afegirMaterial(
            new DVD(9, "El padrí", "Francis Ford Coppola", 1972, 175)
        );

        $biblioteca->afegirMaterial(
            new DVD(10, "Coco", "Lee Unkrich", 2017, 105)
        );

        // 4 usuaris.
        $biblioteca->afegirUsuari(
            new Usuari(1, "Anna Puig", "anna@mail.com")
        );

        $biblioteca->afegirUsuari(
            new Usuari(2, "Marc Soler", "marc@mail.com")
        );

        $biblioteca->afegirUsuari(
            new Usuari(3, "Laura Costa", "laura@mail.com")
        );

        $biblioteca->afegirUsuari(
            new Usuari(4, "Pau Vidal", "pau@mail.com")
        );

        // Préstec vençut: fa 25 dies.
        $dataAntiga = new \DateTime("-25 days");
        $biblioteca->prestarMaterial(1, 1, $dataAntiga);

        // Segon préstec actiu.
        $biblioteca->prestarMaterial(2, 2);

        // Una reserva inicial.
        $biblioteca->reservarMaterial(10, 3);

        return $biblioteca;
    }
}