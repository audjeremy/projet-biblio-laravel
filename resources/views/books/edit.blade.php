@extends('layouts.app')
@section('title','Modifier un livre')

@section('content')
<div class="max-w-3xl mx-auto">
  {{-- Titre --}}
  <div class="mb-6">
    <h1 class="text-2xl font-semibold">Modifier le livre</h1>
    <p class="text-sm text-gray-500">Met à jour les informations ci-dessous puis enregistre.</p>
  </div>

  {{-- Erreurs globales --}}
  @if ($errors->any())
    <div class="mb-4 rounded-md border border-rose-200 bg-rose-50 p-3 text-rose-700">
      <div class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</div>
      <ul class="list-disc ms-5 text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('books.update', $book) }}" class="bg-white rounded-lg shadow p-5 space-y-4">
    @csrf
    @method('PUT')

    {{-- Titre --}}
    <div>
      <label for="title" class="block text-sm font-medium text-gray-700">Titre <span class="text-rose-600">*</span></label>
      <input type="text" id="title" name="title" value="{{ old('title',$book->title) }}" required
             class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
             placeholder="Ex: L’Étranger">
      <x-input-error :messages="$errors->get('title')" class="mt-1 text-sm text-rose-600"/>
    </div>

    {{-- Auteur + Année --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="sm:col-span-2">
        <label for="author" class="block text-sm font-medium text-gray-700">Auteur <span class="text-rose-600">*</span></label>
        <input type="text" id="author" name="author" value="{{ old('author',$book->author) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
               placeholder="Ex: Albert Camus">
        <x-input-error :messages="$errors->get('author')" class="mt-1 text-sm text-rose-600"/>
      </div>
      <div>
        <label for="year" class="block text-sm font-medium text-gray-700">Année</label>
        <input type="number" id="year" name="year" value="{{ old('year',$book->year) }}"
               min="0" max="{{ date('Y') + 1 }}"
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
               placeholder="{{ date('Y') }}">
        <x-input-error :messages="$errors->get('year')" class="mt-1 text-sm text-rose-600"/>
      </div>
    </div>

    {{-- Catégorie + Prix --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="sm:col-span-2">
        <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
        <input type="text" id="category" name="category" value="{{ old('category',$book->category) }}"
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
               placeholder="Ex: Roman">
        <x-input-error :messages="$errors->get('category')" class="mt-1 text-sm text-rose-600"/>
      </div>
      <div>
        <label for="price" class="block text-sm font-medium text-gray-700">Prix <span class="text-rose-600">*</span></label>
        <div class="mt-1 relative">
          <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price',$book->price) }}" required
                 class="block w-full rounded-md border-gray-300 pe-10 focus:border-blue-500 focus:ring-blue-200"
                 placeholder="0.00">
          <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">$</span>
        </div>
        <x-input-error :messages="$errors->get('price')" class="mt-1 text-sm text-rose-600"/>
      </div>
    </div>

    {{-- Résumé --}}
    <div>
      <label for="summary" class="block text-sm font-medium text-gray-700">Résumé</label>
      <textarea id="summary" name="summary" rows="5"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
                placeholder="Brève description du livre…">{{ old('summary',$book->summary) }}</textarea>
      <x-input-error :messages="$errors->get('summary')" class="mt-1 text-sm text-rose-600"/>
    </div>

    {{-- Actions --}}
    <div class="pt-2 flex items-center justify-end gap-2">
      <a href="{{ route('books.index') }}"
         class="px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
        Annuler
      </a>
      <button type="submit"
              class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
        Mettre à jour
      </button>
    </div>
  </form>
</div>
@endsection
