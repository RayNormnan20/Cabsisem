<?php

namespace App\Policies;

use App\Models\Abono;
use App\Models\Abonos;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbonoPolicy
{
    use HandlesAuthorization;

    /**
     * Determinar si el usuario puede crear abonos
     */
    public function create(User $user)
    {
        // Admin siempre puede crear
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Verificar si el usuario tiene rutas asignadas
        return $user->rutas()->exists();
    }

    /**
     * Determinar si el usuario puede guardar un abono específico
     */
    public function store(User $user, Abonos $abono)
    {
        // Admin puede hacer cualquier cosa
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Obtener la ruta del crédito asociado al abono
        $rutaCredito = $abono->credito->id_ruta;
        
        // Verificar si el usuario es cobrador de esa ruta
        return $user->rutas()->where('id_ruta', $rutaCredito)->exists();
    }

    /**
     * Determinar si el usuario puede ver un abono
     */
    public function view(User $user, Abonos $abono)
    {
        return $this->store($user, $abono);
    }

    /**
     * Determinar si el usuario puede actualizar un abono
     */
    public function update(User $user, Abonos $abono)
    {
        return $this->store($user, $abono);
    }

    /**
     * Determinar si el usuario puede eliminar un abono
     */
    public function delete(User $user, Abonos $abono)
    {
        return $this->store($user, $abono);
    }
}