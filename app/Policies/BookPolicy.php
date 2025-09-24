<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{
    /**
     * Tout le monde (connecté ou non) peut voir la liste des livres
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Tout le monde peut voir un livre
     */
    public function view(?User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Seuls les admins peuvent créer
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seuls les admins peuvent modifier
     */
    public function update(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seuls les admins peuvent supprimer
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * (optionnel) Seuls les admins peuvent restaurer
     */
    public function restore(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * (optionnel) Seuls les admins peuvent supprimer définitivement
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }
}
