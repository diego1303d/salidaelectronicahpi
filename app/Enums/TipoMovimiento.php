<?php

namespace App\Enums;

enum TipoMovimiento: string
{
    case Entrada = 'entrada';
    case SalidaVenta = 'salida_venta';
    case SalidaTraspaso = 'salida_traspaso';
    case EntradaTraspaso = 'entrada_traspaso';
    case ReversoCancelacion = 'reverso_cancelacion';
}
