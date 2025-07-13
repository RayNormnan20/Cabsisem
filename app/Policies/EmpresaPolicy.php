<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpresaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_empresa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Empresa $empresa): bool
    {
        // Solo el dueño de la empresa o un super admin puede ver los detalles
        return $user->id === $empresa->user_id || $user->can('view_empresa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_empresa');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Empresa $empresa): bool
    {
        // Solo el dueño de la empresa o un usuario con permiso específico puede actualizar
        return $user->id === $empresa->user_id || $user->can('update_empresa');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Empresa $empresa): bool
    {
        // Solo el dueño de la empresa o un super admin puede eliminar
        return $user->id === $empresa->user_id || $user->can('delete_empresa');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Empresa $empresa): bool
    {
        return $user->can('restore_empresa');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Empresa $empresa): bool
    {
        return $user->can('force_delete_empresa');
    }
}
