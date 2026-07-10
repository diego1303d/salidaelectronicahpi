<?php

namespace App\Enums;

enum SalidaTipo: string
{
    case Venta = 'venta';
    case Traspaso = 'traspaso';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Venta => 'Venta',
            self::Traspaso => 'Traspaso',
        };
    }
}
