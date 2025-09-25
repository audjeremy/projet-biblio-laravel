@extends('layouts.app')
@section('title','Contacter nous')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Contacter nous</h1>
    <a href="{{ route('books.index') }}" class="text-sm text-blue-600 hover:underline">← Retour aux livres</a>
  </div>

  {{-- Flash --}}
  @foreach (['success','error'] as $f)
    @if(session($f))
      <div class="mb-4 p-3 rounded-md {{ $f==='error' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
        {{ session($f) }}
      </div>
    @endif
  @endforeach

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Formulaire --}}
    <div class="lg:col-span-2">
      <div class="bg-white rounded-lg shadow border p-6">
        <form method="POST" action="{{ route('messages.store') }}" class="space-y-4">
          @csrf

          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
            @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
            @error('email') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Sujet</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                   class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
            @error('subject') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea id="message" name="message" rows="6"
                   class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">{{ old('message') }}</textarea>
            @error('message') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          <div class="pt-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Envoyer
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Infos + Carte --}}
    <aside class="space-y-6">
      {{-- Carte Google Maps (Collège Ahuntsic) --}}
      <div class="bg-white rounded-lg shadow border overflow-hidden">
        <div class="p-4 border-b">
          <h2 class="text-lg font-semibold">Où nous trouver</h2>
          <p class="text-sm text-gray-600">Collège Ahuntsic</p>
          <p class="text-sm text-gray-600">915 Rue Saint-Hubert, Montréal, QC H2M 1Y8</p>
        </div>

        {{-- Iframe responsive avec ratio 16:9 --}}
        <div class="relative w-full" style="padding-top: 56.25%;">
          <iframe
            title="Carte - Collège Ahuntsic"
            class="absolute inset-0 w-full h-full"
            style="border:0;"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps?q=Coll%C3%A8ge%20Ahuntsic%2C%20915%20Rue%20Saint-Hubert%2C%20Montr%C3%A9al%2C%20QC%20H2M%201Y8&output=embed">
          </iframe>
        </div>
      </div>

      {{-- Coordonnées (exemple) --}}
      <div class="bg-white rounded-lg shadow border p-4">
        <h3 class="text-lg font-semibold mb-2">Coordonnées</h3>
        <ul class="space-y-1 text-sm text-gray-700">
          <li><span class="font-medium">Adresse: </span>915 Rue Saint-Hubert, Montréal, QC H2M 1Y8</li>
          <li><span class="font-medium">Téléphone: </span>(514) 389-5921</li>
          <li><span class="font-medium">Courriel: </span><a href="mailto:info@librairie-ej.test" class="text-blue-600 hover:underline">info@librairie-ej.test</a></li>
          <li><span class="font-medium">Heures: </span>Lun–Ven 8h–18h</li>
          <li><span class="font-medium">Sam-Dim: </span>9h-17h</li>
        </ul>
      </div>
    </aside>
  </div>
</div>
@endsection