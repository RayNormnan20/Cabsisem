<?php

namespace App\Policies;

use App\Models\Clientes;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ClientesPolicy
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
        return $user->can('Listar Clientes')
            ? Response::allow()
            : Response::deny('No tienes permiso para listar clientes.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clientes  $cliente
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Clientes $cliente)
    {
        // Permiso básico + lógica adicional para clientes asignados
        return $user->can('Ver Cliente') || 
               $this->isClientAssigned($user, $cliente)
            ? Response::allow()
            : Response::deny('No tienes permiso para ver este cliente.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('Crear Cliente')
            ? Response::allow()
            : Response::deny('No tienes permiso para crear clientes.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clientes  $cliente
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Clientes $cliente)
    {
        return $user->can('Actualizar Cliente') || 
               ($this->isClientAssigned($user, $cliente) && $user->can('Actualizar Clientes Asignados'))
            ? Response::allow()
            : Response::deny('No tienes permiso para actualizar este cliente.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clientes  $cliente
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Clientes $cliente)
    {
        return $user->can('Eliminar Cliente')
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar este cliente.');
    }

    /**
     * Determine whether the user can import clients.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
  

    /**
     * Check if client is assigned to user's route
     */
    protected function isClientAssigned(User $user, Clientes $cliente): bool
    {
        return $user->rutas()->where('id_ruta', $cliente->id_ruta)->exists();
    }
}