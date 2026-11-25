<?php

namespace App\Traits;

/**
 * Afegeix als materials la funcionalitat per registrar i consultar el seu historial d’accions.
 */
trait Auditoria
{
    private array $historial = [];

    public function registrarAccio(string $accio, string $detalls = ''): void
    {
        $this->historial[] = [
            'data' => date('Y-m-d H:i:s'),
            'accio' => $accio,
            'detalls' => $detalls
        ];
    }

    public function obtenirHistorial(): array
    {
        return $this->historial;
    }

    public function obtenirUltimaAccio(): ?array
    {
        if (empty($this->historial)) {
            return null;
        }

        return end($this->historial);
    }

    public function netejarHistorial(): void
    {
        $this->historial = [];
    }
}