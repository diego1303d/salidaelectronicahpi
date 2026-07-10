<?php

namespace App\Enums;

enum SalidaEstatus: string
{
    case Pendiente = 'pendiente';
    case Entregada = 'entregada';
    case Cancelada = 'cancelada';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente por entregar',
            self::Entregada => 'Entregada',
            self::Cancelada => 'Cancelada',
        };
    }
}
