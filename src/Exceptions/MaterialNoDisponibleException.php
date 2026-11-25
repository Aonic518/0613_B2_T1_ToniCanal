<?php
namespace App\Exceptions;

/**
 * Excepció que s’utilitza quan un material no està disponible per prestar-lo o reservar-lo.
 */
class MaterialNoDisponibleException extends \Exception
{
    private int $materialId;

    public function __construct(
        int $materialId,
        string $missatge = "El material no està disponible"
    ) {
        parent::__construct($missatge);
        $this->materialId = $materialId;
    }

    public function getMaterialId(): int
    {
        return $this->materialId;
    }

    public function __toString(): string
    {
        return "Material #" . $this->materialId . ": " . $this->message;
    }
}