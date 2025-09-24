<?php

use App\Models\User;

function makeAdmin(): User {
    return User::factory()->create(['role' => 'admin']);
}

function makeUser(): User {
    return User::factory()->create(['role' => 'user']);
}
