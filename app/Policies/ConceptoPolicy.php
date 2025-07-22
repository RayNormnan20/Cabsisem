<?php

namespace App\Policies;

use App\Models\Concepto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ConceptoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('Listar Conceptos')
            ? Response::allow()
            : Response::deny('No tienes permiso para listar conceptos.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Concepto $concepto)
    {
        return $user->can('Ver Concepto')
            ? Response::allow()
            : Response::deny('No tienes permiso para ver este concepto.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Solo administradores pueden crear conceptos
        return $user->hasRole('Administrador') && $user->can('Crear Concepto')
            ? Response::allow()
            : Response::deny('No tienes permiso para crear conceptos.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Concepto $concepto)
    {
        // Solo administradores pueden actualizar conceptos
        // Restringir actualización de conceptos del sistema
        return $user->hasRole('Administrador') && 
               $user->can('Actualizar Concepto') &&
               !$concepto->sistema
            ? Response::allow()
            : Response::deny('No tienes permiso para actualizar este concepto o es un concepto del sistema.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Concepto $concepto)
    {
        // Solo administradores pueden eliminar conceptos
        // No se pueden eliminar conceptos del sistema o con movimientos asociados
        return $user->hasRole('Administrador') && 
               $user->can('Eliminar Concepto') &&
               !$concepto->sistema &&
               $concepto->abonos()->count() === 0
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar este concepto, es un concepto del sistema o tiene movimientos asociados.');
    }

    /**
     * Determine whether the user can manage concept types.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageTypes(User $user)
    {
        return $user->can('Gestionar Tipos Concepto')
            ? Response::allow()
            : Response::deny('No tienes permiso para gestionar tipos de concepto.');
    }
}