@extends('layouts.app')
@section('title','Ajouter un livre')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl font-semibold mb-6">Ajouter un livre</h1>

  {{-- === Affichage des erreurs de validation === --}}
  @if ($errors->any())
    <div class="mb-4 rounded-md border border-rose-200 bg-rose-50 text-rose-700 p-3">
      <ul class="list-disc ms-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- === Formulaire d'ajout de livre === --}}
  <form method="POST" action="{{ route('books.store') }}" class="space-y-4">
    @csrf

    {{-- Champ titre du livre --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Titre <span class="text-rose-600">*</span></label>
      <input name="title" value="{{ old('title') }}" required
             class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      {{-- Champ auteur --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Auteur <span class="text-rose-600">*</span></label>
        <input name="author" value="{{ old('author') }}" required
               class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
      </div>
      {{-- Champ catégorie --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Catégorie</label>
        <input name="category" value="{{ old('category') }}"
               class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      {{-- Champ année --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Année</label>
        <input type="number" name="year" value="{{ old('year') }}"
               class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
      </div>
      {{-- Champ prix --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Prix <span class="text-rose-600">*</span></label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required
               class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
      </div>
    </div>

    {{-- Champ résumé --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Résumé</label>
      <textarea name="summary" rows="5"
                class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">{{ old('summary') }}</textarea>
    </div>

    {{-- Optionnel : pour tester les promos rapidement si la colonne discount existe --}}
    @if(Schema::hasColumn('books', 'discount'))
      <div>
    <label for="discount" class="block text-sm font-medium text-gray-700">Remise (%)</label>
    <input type="number" step="0.01" min="0" max="100" name="discount" id="discount"
           value="{{ old('discount', $book->discount ?? 0) }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
</div>
    @endif

    <div class="flex items-center gap-2 pt-2">
      <a href="{{ route('books.index') }}"
         class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Annuler</a>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Enregistrer</button>
    </div>
  </form>
</div>
@endsection