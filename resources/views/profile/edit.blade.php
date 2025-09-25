@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold mb-6">Mon profil</h1>

    {{-- Message de succès --}}
    @if(session('status'))
        <div class="mb-4 p-3 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Informations du profil --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Informations du compte</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <span class="text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse courriel</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <span class="text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        {{-- Mot de passe --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Changer le mot de passe</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('current_password')
                        <span class="text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <span class="text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Suppression du compte --}}
    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-rose-600">Supprimer le compte</h2>
        <p class="text-sm text-gray-600 mb-4">
            Une fois le compte supprimé, toutes les données seront définitivement perdues.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="flex justify-end">
                <button type="submit"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?')"
                        class="px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-700">
                    Supprimer le compte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection