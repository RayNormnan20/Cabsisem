<?php

namespace App\Policies;

use App\Models\Ruta;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RutaPolicy
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
        return $user->can('Listar Rutas')
            ? Response::allow()
            : Response::deny('No tienes permiso para listar rutas.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ruta  $ruta
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ruta $ruta)
    {
        return $user->can('Ver Ruta')
            ? Response::allow()
            : Response::deny('No tienes permiso para ver esta ruta.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('Crear Ruta')
            ? Response::allow()
            : Response::deny('No tienes permiso para crear rutas.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ruta  $ruta
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Ruta $ruta)
    {
        return $user->can('Actualizar Ruta')
            ? Response::allow()
            : Response::deny('No tienes permiso para actualizar esta ruta.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ruta  $ruta
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ruta $ruta)
    {
        return $user->can('Eliminar Ruta')
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar esta ruta.');
    }

    /**
     * Determine whether the user can assign routes.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assign(User $user)
    {
        return $user->can('Asignar Rutas')
            ? Response::allow()
            : Response::deny('No tienes permiso para asignar rutas.');
    }

    /**
     * Determine whether the user can generate route reports.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function report(User $user)
    {
        return $user->can('Reportes Rutas')
            ? Response::allow()
            : Response::deny('No tienes permiso para generar reportes de rutas.');
    }
}