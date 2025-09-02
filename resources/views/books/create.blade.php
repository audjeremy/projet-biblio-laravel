@extends('layouts.app')

@section('content')
<h1>Ajouter un livre</h1>

<form method="POST" action="{{ route('books.store') }}">
    @csrf
    <input type="text" name="title" placeholder="Titre" required>
    <input type="text" name="author" placeholder="Auteur" required>
    <input type="text" name="category" placeholder="Catégorie">
    <input type="number" name="year" placeholder="Année">
    <textarea name="summary" placeholder="Résumé"></textarea>
    <input type="number" step="0.01" name="price" placeholder="Prix" required>
    <button type="submit">Enregistrer</button>
</form>
@endsection
