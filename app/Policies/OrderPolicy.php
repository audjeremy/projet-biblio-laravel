<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Un user peut voir une commande s'il en est le propriétaire
     * ou s'il est admin.
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id || ($user->role ?? 'user') === 'admin';
    }

    /**
     * (Optionnel) L'admin peut voir la liste complète ; sinon, chacun sa liste.
     */
    public function viewAny(User $user): bool
    {
        return true; // laisser l'index filtrer côté contrôleur si tu veux
    }
}