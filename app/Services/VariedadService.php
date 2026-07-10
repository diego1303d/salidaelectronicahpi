<?php
namespace App\Services;

use App\Models\Variedad;
use Illuminate\Support\Facades\Hash;

class VariedadService
{
    /**
     * Lógica para registrar un usuario.
     */
    public function register(array $data): Variedad
    {
         $variedad = Variedad::create($data);   // ← INSERT a la tabla variedades

        // Aquí podrías disparar un evento, enviar un email de bienvenida, etc.
        // event(new UserRegistered($user));

        return $variedad;
    }
}