<?php
namespace App\Exceptions;

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