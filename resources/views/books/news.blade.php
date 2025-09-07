@extends('layouts.app')

@section('content')
<h1>Nouveautés (10 derniers jours)</h1>

<ul>
@forelse ($books as $book)
    <li>{{ $book->title }} - {{ $book->author }} ({{ $book->created_at->format('d/m/Y') }})</li>
@empty
    <li>Aucun nouveau livre</li>
@endforelse
</ul>
@endsection
