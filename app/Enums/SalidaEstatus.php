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


    // App\Enums\SalidaEstatus
public function color(): string
{
    return match ($this) {
        self::Pendiente => 'bg-yellow-100 text-yellow-800 border-yellow-400',
        self::Entregada => 'bg-green-100 text-green-800 border-green-400',
        self::Cancelada => 'bg-red-100 text-red-800 border-red-400',
    };
}
}
