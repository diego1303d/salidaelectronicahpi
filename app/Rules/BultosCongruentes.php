<?php

namespace App\Rules;

use App\Models\Variedad;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida que los bultos capturados sean congruentes con las toneladas
 * segun el peso por bulto de la variedad (con tolerancia).
 * Se aplica al campo detalles.*.bultos de entradas Y salidas.
 */
class BultosCongruentes implements ValidationRule, DataAwareRule
{
    /** Tolerancia de desviacion permitida (0.10 = 10%) */
    private const TOLERANCIA = 0.10;

    private array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $attribute llega como "detalles.2.bultos" → sacamos el índice del renglón
        preg_match('/detalles\.(\d+)\.bultos/', $attribute, $m);
        $renglon = $this->data['detalles'][$m[1]] ?? null;

        if (! $renglon || empty($renglon['variedad_id']) || empty($renglon['toneladas'])) {
            return; // las reglas básicas ya se quejaron de lo que falte
        }

        $variedad = Variedad::find($renglon['variedad_id']);

        // Sin variedad válida o sin peso configurado, no podemos verificar
        if (! $variedad || ! $variedad->peso_bulto_kg) {
            return;
        }

        if (! is_numeric($renglon['toneladas']) || ! is_numeric($value)) {
            return;
        }

        $esperados  = $variedad->bultosEsperados((float) $renglon['toneladas']);
        $capturados = (int) $value;
        $desviacion = abs($capturados - $esperados) / max($esperados, 1);

        if ($desviacion > self::TOLERANCIA) {
            $fail(
                "Los bultos no cuadran con las toneladas para {$variedad->nombre}: " .
                "para {$renglon['toneladas']} t se esperan ~{$esperados} bultos y capturaste {$capturados}. Revisa la captura."
            );
        }
    }
}