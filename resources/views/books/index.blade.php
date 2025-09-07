@extends('layouts.app')

@section('content')
<h1>Liste des livres</h1>

<form method="GET" action="{{ route('books.index') }}">
    <input type="text" name="q" placeholder="Recherche titre/auteur/année" value="{{ request('q') }}">
    <button type="submit">Chercher</button>
</form>

<a href="{{ route('books.create') }}">+ Ajouter un livre</a>

<table>
    <thead>
        <tr>
            <th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Année</th><th>Prix</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($books as $book)
        <tr>
            <td><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->category }}</td>
            <td>{{ $book->year }}</td>
            <td>{{ $book->price }} $</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $books->links() }}
@endsection
