<?php

namespace App\Enums;

enum FormaPago: string
{
    case Contado = 'contado';
    case Credito = 'credito';
    case CincuentaCincuenta = '50_50';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Contado => 'Contado',
            self::Credito => 'Crédito',
            self::CincuentaCincuenta => '50 / 50',
        };
    }
}
