<?php
namespace App\Models;

use App\Interfaces\Reservable;
use App\Exceptions\MaterialNoTrobatException;
use App\Exceptions\MaterialNoDisponibleException;
use App\Exceptions\MaterialNoPrestatException;
use App\Exceptions\UsuariNoTrobatException;

class Biblioteca
{
    private array $materials = [];
    private array $usuaris = [];
    private array $prestecs = [];

    public function __construct(private string $nom)
    {
    }

    // Afegeix un material a la biblioteca.
    public function afegirMaterial(Material $material): void
    {
        $this->materials[$material->getId()] = $material;
    }

    public function eliminarMaterial(int $id): bool
    {
        if (!isset($this->materials[$id])) {
            return false;
        }

        unset($this->materials[$id]);
        return true;
    }

    public function cercarPerId(int $id): ?Material
    {
        return $this->materials[$id] ?? null;
    }

    public function cercarPerTitol(string $titol): array
    {
        return array_filter($this->materials, function ($material) use ($titol) {
            return stripos($material->getTitol(), $titol) !== false;
        });
    }

    public function cercarPerAutor(string $autor): array
    {
        return array_filter($this->materials, function ($material) use ($autor) {
            return stripos($material->getAutor(), $autor) !== false;
        });
    }

    public function llistarDisponibles(): array
    {
        return array_filter(
            $this->materials,
            fn($m) => $m->isDisponible()
        );
    }

    public function llistarPrestats(): array
    {
        return array_filter(
            $this->materials,
            fn($m) => !$m->isDisponible()
        );
    }

    public function llistarPerTipus(string $tipus): array
    {
        return array_filter(
            $this->materials,
            fn($m) => strcasecmp($m->getTipus(), $tipus) === 0
        );
    }

    public function llistarPrestecsActius(): array
    {
        return array_filter(
            $this->prestecs,
            fn($p) => $p->estaActiu()
        );
    }

    public function llistarReserves(): array
    {
        return array_filter(
            $this->materials,
            fn($m) => $m instanceof Reservable && $m->estaReservat()
        );
    }

    public function llistarPrestecsUsuari(int $usuariId): array
    {
        return array_filter(
            $this->prestecs,
            fn($p) => $p->getUsuari()->getId() === $usuariId
        );
    }

    public function afegirUsuari(Usuari $usuari): void
    {
        $this->usuaris[$usuari->getId()] = $usuari;
    }

    public function cercarUsuari(int|string $identificador): ?Usuari
    {
        if (is_int($identificador)) {
            return $this->usuaris[$identificador] ?? null;
        }

        foreach ($this->usuaris as $usuari) {
            if (strcasecmp($usuari->getNom(), $identificador) === 0) {
                return $usuari;
            }
        }

        return null;
    }

    // Crea un préstec i actualitza el material i l'usuari.
    public function prestarMaterial(
        int $materialId,
        int $usuariId,
        ?\DateTime $dataPrestec = null
    ): Prestec {
        $material = $this->cercarPerId($materialId);

        if ($material === null) {
            throw new MaterialNoTrobatException(
                "No existeix el material amb ID $materialId"
            );
        }

        $usuari = $this->cercarUsuari($usuariId);

        if ($usuari === null) {
            throw new UsuariNoTrobatException(
                "No existeix l'usuari amb ID $usuariId"
            );
        }

        if (!$material->isDisponible()) {
            throw new MaterialNoDisponibleException(
                $materialId,
                "El material ja està prestat."
            );
        }

        if ($material instanceof Reservable && $material->estaReservat()) {
            if ($material->getUsuariReserva() !== $usuari->getNom()) {
                throw new MaterialNoDisponibleException(
                    $materialId,
                    "El material està reservat per un altre usuari."
                );
            }

            $material->cancelarReserva();
        }

        if (!$material->prestar()) {
            throw new MaterialNoDisponibleException($materialId);
        }

        $prestec = new Prestec($material, $usuari, 14, $dataPrestec);

        $this->prestecs[] = $prestec;
        $usuari->afegirPrestec($material);

        $usuari->afegirNotificacio("Préstec realitzat correctament.");

        return $prestec;
    }

    // Retorna un material i calcula la multa.
    public function retornarMaterial(int $materialId): float
    {
        $material = $this->cercarPerId($materialId);

        if ($material === null) {
            throw new MaterialNoTrobatException(
                "No existeix el material amb ID $materialId"
            );
        }

        foreach ($this->prestecs as $prestec) {
            if (
                $prestec->getMaterial()->getId() === $materialId
                && $prestec->estaActiu()
            ) {
                $prestec->retornar();
                $material->retornar();

                $prestec->getUsuari()->eliminarPrestec($materialId);
                $prestec->getUsuari()->afegirNotificacio(
                    "Material retornat correctament."
                );

                return $prestec->calcularMulta();
            }
        }

        throw new MaterialNoPrestatException(
            "Aquest material no té cap préstec actiu."
        );
    }

    // Reserva només materials que implementen Reservable.
    public function reservarMaterial(int $materialId, int $usuariId): bool
    {
        $material = $this->cercarPerId($materialId);

        if ($material === null) {
            throw new MaterialNoTrobatException(
                "No existeix el material amb ID $materialId"
            );
        }

        $usuari = $this->cercarUsuari($usuariId);

        if ($usuari === null) {
            throw new UsuariNoTrobatException(
                "No existeix l'usuari amb ID $usuariId"
            );
        }

        if (!$material instanceof Reservable) {
            throw new MaterialNoDisponibleException(
                $materialId,
                "Les revistes no es poden reservar."
            );
        }

        if (!$material->isDisponible()) {
            throw new MaterialNoDisponibleException(
                $materialId,
                "El material està prestat."
            );
        }

        if (!$material->reservar($usuari->getNom())) {
            throw new MaterialNoDisponibleException(
                $materialId,
                "El material ja està reservat."
            );
        }

        $usuari->afegirNotificacio("Reserva realitzada correctament.");

        return true;
    }

    // Estadístiques generals de la biblioteca.
    public function obtenirEstadistiques(): array
    {
        $perTipus = [
            "Llibre" => count($this->llistarPerTipus("Llibre")),
            "Revista" => count($this->llistarPerTipus("Revista")),
            "DVD" => count($this->llistarPerTipus("DVD"))
        ];

        $multes = 0;

        foreach ($this->llistarPrestecsActius() as $prestec) {
            $multes += $prestec->calcularMulta();
        }

        return [
            "total" => count($this->materials),
            "disponibles" => count($this->llistarDisponibles()),
            "prestats" => count($this->llistarPrestats()),
            "reservats" => count($this->llistarReserves()),
            "perTipus" => $perTipus,
            "multesPendents" => $multes
        ];
    }

    // Permet cridar mètodes dinàmics com getLlibres() o getDVDs().
    public function __call(string $metode, array $arguments): mixed
    {
        return match ($metode) {
            "getLlibres" => $this->llistarPerTipus("Llibre"),
            "getRevistes" => $this->llistarPerTipus("Revista"),
            "getDVDs" => $this->llistarPerTipus("DVD"),
            "getMaterials" => $this->materials,
            "getUsuaris" => $this->usuaris,
            "getNom" => $this->nom,
            default => throw new \BadMethodCallException(
                "El mètode $metode no existeix."
            )
        };
    }
}