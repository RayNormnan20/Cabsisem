<?php

namespace App\Policies;

use App\Models\PlanillaRecaudador;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PlanillaRecaudadorPolicy
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
        return $user->can('Listar Planilla Recaudadors')
            ? Response::allow()
            : Response::deny('No tienes permiso para listar planillas de recaudador.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PlanillaRecaudador  $planilla
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PlanillaRecaudador $planilla)
    {
        // Permiso general o si pertenece a la ruta del recaudador
        return $user->can('Ver Planilla Recaudador') || 
               $user->rutas->contains($planilla->id_ruta)
            ? Response::allow()
            : Response::deny('No tienes permiso para ver esta planilla.');
    }
}