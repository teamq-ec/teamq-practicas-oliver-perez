<?php

namespace App\Policies;

use App\Models\Usuario;
namespace App\Policies;
use App\Models\Usuario;

class UsuarioPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        return false; // No permitir ver todos los modelos
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario): bool
    {
        // Puedes implementar lógica adicional aquí si es necesario
        return true; // Permitir ver el modelo
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        return false; // No permitir crear modelos
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario): bool
    {
        return false; // No permitir actualizar el modelo
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario): bool
    {
        return false; // No permitir eliminar el modelo
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario): bool
    {
        return false; // No permitir restaurar el modelo
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario): bool
    {
        return false; // No permitir eliminar permanentemente el modelo
    }

    /**
     * Determine whether the user has the role "Asesor".
     */
    public function isAsesor(Usuario $usuario)
    {
        return $usuario->rol_id === 2;
    }
}
