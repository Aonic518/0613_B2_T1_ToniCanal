<?php
namespace App\Models;

/**
 * Representa un préstec i controla les dates, els retards i les multes associades.
 */
class Prestec
{
    private \DateTime $dataPrestec;
    private ?\DateTime $dataRetorn = null;

    public function __construct(
        private Material $material,
        private Usuari $usuari,
        private int $diesLimitPrestec = 14,
        ?\DateTime $dataPrestec = null
    ) {
        $this->dataPrestec = $dataPrestec ?? new \DateTime();
    }

    public function retornar(): void
    {
        $this->dataRetorn = new \DateTime();
    }

    public function estaActiu(): bool
    {
        return $this->dataRetorn === null;
    }

    public function calcularDiesRetard(): int
    {
        $dataFinal = $this->dataRetorn ?? new \DateTime();

        $diesTranscorreguts = (int) $this->dataPrestec
            ->diff($dataFinal)
            ->format("%r%a");

        return max(0, $diesTranscorreguts - $this->diesLimitPrestec);
    }

    public function calcularMulta(): float
    {
        return $this->material->calcularMulta(
            $this->calcularDiesRetard()
        );
    }

    public function estaVencut(): bool
    {
        return $this->getDiesPendents() < 0;
    }

    public function getDiesPendents(): int
    {
        $dataFinal = $this->dataRetorn ?? new \DateTime();

        $diesTranscorreguts = (int) $this->dataPrestec
            ->diff($dataFinal)
            ->format("%r%a");

        return $this->diesLimitPrestec - $diesTranscorreguts;
    }

    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function getUsuari(): Usuari
    {
        return $this->usuari;
    }

    public function getDataPrestec(): \DateTime
    {
        return $this->dataPrestec;
    }

    public function getDataRetorn(): ?\DateTime
    {
        return $this->dataRetorn;
    }

    public function getDiesLimitPrestec(): int
    {
        return $this->diesLimitPrestec;
    }
}