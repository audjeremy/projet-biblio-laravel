@extends('layouts.app')
@section('title','Contacter Nous')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl font-semibold mb-4">Contacter Nous</h1>

  @if(session('ok'))
    <div class="mb-4 p-3 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
      {{ session('ok') }}
    </div>
  @endif

  <form method="POST" action="{{ route('messages.store') }}" class="bg-white rounded-lg shadow p-5 space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700">Nom</label>
      <input name="name" value="{{ old('name') }}" required
             class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200 @error('name') border-rose-400 @enderror">
      <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-rose-600"/>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required
             class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200 @error('email') border-rose-400 @enderror">
      <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-rose-600"/>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Sujet (optionnel)</label>
      <input name="subject" value="{{ old('subject') }}"
             class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200 @error('subject') border-rose-400 @enderror">
      <x-input-error :messages="$errors->get('subject')" class="mt-1 text-sm text-rose-600"/>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Message</label>
      <textarea name="message" rows="5" required
                class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200 @error('message') border-rose-400 @enderror">{{ old('message') }}</textarea>
      <x-input-error :messages="$errors->get('message')" class="mt-1 text-sm text-rose-600"/>
    </div>

    <div class="flex justify-end gap-2">
      <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Annuler</a>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Envoyer</button>
    </div>
  </form>

  <hr class="my-8">

  <div class="text-gray-600">
    <h2 class="text-lg font-semibold mb-2">Informations de la Librairie E-J</h2>
    <p>Adresse: 123 Rue Principale, Montréal</p>
    <p>Téléphone: (514) 555-1234 — Email: librairieej@mail.com</p>
  </div>
</div>
@endsection
