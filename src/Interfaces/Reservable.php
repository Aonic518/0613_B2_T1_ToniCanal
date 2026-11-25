<?php

namespace App\Interfaces;

/**
 * Defineix els mètodes que ha de tenir qualsevol material que es pugui reservar.
 */
interface Reservable
{
    public function reservar(string $nomUsuari): bool;

    public function cancelarReserva(): bool;

    public function estaReservat(): bool;

    public function getUsuariReserva(): ?string;
}