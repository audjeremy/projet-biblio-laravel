<?php

use App\Models\Message;
use Illuminate\Support\Facades\Route;

// Routes qu'on attend (déjà créées dans web.php)
beforeAll(function () {
    expect(Route::has('admin.messages.index'))->toBeTrue();
    expect(Route::has('admin.messages.read'))->toBeTrue();
    expect(Route::has('admin.messages.destroy'))->toBeTrue();
});

it('redirects guests to login', function () {
    $response = $this->get(route('admin.messages.index'));
    $response->assertRedirect(route('login'));
});

it('forbids non-admin users', function () {
    $user = makeUser();
    $response = $this->actingAs($user)->get(route('admin.messages.index'));
    $response->assertStatus(403);
});

it('lets admin list messages', function () {
    $admin = makeAdmin();
    $messages = Message::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.messages.index'));

    $response->assertOk();
    // Vérifie que le contenu est visible
    $response->assertSee(e($messages[0]->name));
    $response->assertSee(e($messages[0]->email));
    $response->assertSee(e($messages[0]->subject));
});

it('marks a message as read', function () {
    $admin = makeAdmin();
    $m = Message::factory()->create(['is_read' => false]);

    $response = $this->actingAs($admin)->patch(route('admin.messages.read', $m));
    $response->assertRedirect(); // back()
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('messages', [
        'id' => $m->id,
        'is_read' => true,
    ]);
});

it('deletes a message', function () {
    $admin = makeAdmin();
    $m = Message::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.messages.destroy', $m));
    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('messages', ['id' => $m->id]);
});
