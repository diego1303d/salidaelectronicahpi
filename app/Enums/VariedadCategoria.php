<?php
 
namespace App\Enums;
 
/**
 * Categorías de semilla de trigo.
 *
 * Los values (CERTIFICADO, REGISTRADO, DECLARADO) coinciden EXACTO
 * con el ENUM de la columna `categoria` en MySQL. Si algún día
 * agregas una categoría, se agrega aquí Y en la migración — un solo
 * lugar en el código PHP, cero strings sueltos regados por el proyecto.
 */
enum VariedadCategoria: string
{
    case Certificado = 'CERTIFICADO';
    case Registrado  = 'REGISTRADO';
    case Declarado   = 'DECLARADO';
 
    /**
     * Etiqueta bonita para mostrar en pantalla (selects, tablas, PDFs).
     */
    public function label(): string
    {
        return match ($this) {
            self::Certificado => 'Certificado',
            self::Registrado  => 'Registrado',
            self::Declarado   => 'Declarado',
        };
    }
}
 