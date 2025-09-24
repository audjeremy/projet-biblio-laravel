<?php

use App\Models\Message;
use Illuminate\Support\Facades\Route;

beforeAll(function () {
    // si tu n'as pas de page contact, commente ce bloc
    expect(Route::has('contact.submit'))->toBeTrue();
});

it('stores a contact form submission', function () {
    $payload = [
        'name' => 'Alice Test',
        'email' => 'alice@example.com',
        'subject' => 'Question',
        'message' => 'Bonjour, ceci est un test.',
    ];

    $response = $this->post(route('contact.submit'), $payload);
    $response->assertRedirect(); // back
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('messages', [
        'name' => 'Alice Test',
        'email' => 'alice@example.com',
        'subject' => 'Question',
        'message' => 'Bonjour, ceci est un test.',
        // is_read par défaut = false si ta colonne existe avec default(false)
    ]);
});
