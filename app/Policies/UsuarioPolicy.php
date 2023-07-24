<?php

namespace App\Policies;

use App\Constants;
use App\Models\User;

class UsuarioPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $usuario): bool
    {
        return false; // No permitir ver todos los modelos
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $usuario): bool
    {
        // Puedes implementar lógica adicional aquí si es necesario
        return true; // Permitir ver el modelo
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $usuario): bool
    {
        return false; // No permitir crear modelos
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $usuario): bool
    {
        return false; // No permitir actualizar el modelo
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $usuario): bool
    {
        return false; // No permitir eliminar el modelo
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $usuario): bool
    {
        return false; // No permitir restaurar el modelo
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $usuario): bool
    {
        return false; // No permitir eliminar permanentemente el modelo
    }

    /**
     * Determine whether the user has the role "Asesor".
     */
    
     public function isAdministrador(User $usuario)
     {
         return $usuario->rol_id === Constants::ROL_ADMINISTRADOR;
     }
     
     public function isAsesor(User $usuario)
    {
        return $usuario->rol_id === Constants::ROL_ASESOR;
    }
}
