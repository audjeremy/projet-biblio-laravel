@extends('layouts.app')
@section('title','Contacter nous')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- ===================== Formulaire ===================== --}}
    <div class="lg:col-span-2">
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <h1 class="text-2xl font-semibold mb-1">Nous écrire</h1>
        <p class="text-sm text-gray-500 mb-6">Remplissez le formulaire, nous vous répondrons rapidement.</p>

        {{-- Flash success --}}
        @if(session('ok'))
          <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-emerald-700">
            {{ session('ok') }}
          </div>
        @endif

        {{-- Erreurs --}}
        @if ($errors->any())
          <div class="mb-4 rounded-md border border-rose-200 bg-rose-50 p-3 text-rose-700">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('messages.store') }}" class="space-y-4">
          @csrf

          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input id="name" name="name" type="text" required maxlength="255"
                   value="{{ old('name') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Courriel</label>
            <input id="email" name="email" type="email" required maxlength="255"
                   value="{{ old('email') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Sujet (optionnel)</label>
            <input id="subject" name="subject" type="text" maxlength="255"
                   value="{{ old('subject') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea id="message" name="message" rows="6" required maxlength="5000"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">{{ old('message') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Max 5000 caractères.</p>
          </div>

          {{-- Anti-spam très simple (champ caché, ignoré côté serveur) --}}
          <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

          <div class="pt-2">
            <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Envoyer
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- ===================== Coordonnées ===================== --}}
    <aside class="lg:col-span-1">
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-4">Coordonnées</h2>

        <dl class="space-y-3 text-sm">
          <div>
            <dt class="font-medium text-gray-700">Bibliothèque</dt>
            <dd class="text-gray-700">{{ config('app.name') }}</dd>
          </div>

          <div>
            <dt class="font-medium text-gray-700">Adresse</dt>
            <dd class="text-gray-700">
              123 Rue Principale<br>
              Montréal, QC H1A 2B3
            </dd>
          </div>

          <div>
            <dt class="font-medium text-gray-700">Téléphone</dt>
            <dd class="text-gray-700">
              <a href="tel:+15145551234" class="text-blue-600 hover:underline">+1 (514) 555-1234</a>
            </dd>
          </div>

          <div>
            <dt class="font-medium text-gray-700">Courriel</dt>
            <dd class="text-gray-700">
              <a href="mailto:contact@exemple.com" class="text-blue-600 hover:underline">contact@exemple.com</a>
            </dd>
          </div>

          <div>
            <dt class="font-medium text-gray-700">Heures d’ouverture</dt>
            <dd class="text-gray-700">
              Lun–Ven : 9h–18h<br>
              Sam : 10h–16h<br>
              Dim : Fermé
            </dd>
          </div>
        </dl>

        <hr class="my-4">

        <p class="text-xs text-gray-500">
          Vous pouvez aussi nous joindre via le formulaire. Les messages sont traités
          en priorité pendant les heures d’ouverture.
        </p>
      </div>
    </aside>
  </div>
</div>
@endsection