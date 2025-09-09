@extends('layouts.app')

@section('title', $book->title)

@section('content')
  <h1 style="margin-bottom: .5rem;">{{ $book->title }}</h1>
  <p style="margin:0 0 1rem;color:#555;">
    <em>Créé le {{ $book->created_at->format('d/m/Y H:i') }}
    • Modifié le {{ $book->updated_at->format('d/m/Y H:i') }}</em>
  </p>

  <dl style="display:grid;grid-template-columns: 180px 1fr;gap:.35rem .75rem;max-width:800px">
    <dt style="font-weight:700">Auteur</dt>
    <dd>{{ $book->author }}</dd>

    <dt style="font-weight:700">Catégorie</dt>
    <dd>{{ $book->category ?? '—' }}</dd>

    <dt style="font-weight:700">Année</dt>
    <dd>{{ $book->year ?? '—' }}</dd>

    <dt style="font-weight:700">Prix</dt>
    <dd>{{ number_format($book->price, 2, ',', ' ') }} $</dd>

    <dt style="font-weight:700">Résumé</dt>
    <dd>{{ $book->summary ?? '—' }}</dd>
  </dl>

  <div style="margin-top:1.25rem; display:flex; gap:.5rem;">
    {{-- Lien retour vers la liste --}}
    <a href="{{ url()->previous() === url()->current() ? route('books.index') : url()->previous() }}"
       style="padding:.5rem .75rem;border:1px solid #ccc;border-radius:.5rem;text-decoration:none;">
      ← Retour
    </a>

    {{-- edit -- }}
    {{-- <a href="{{ route('books.edit', $book) }}" style="padding:.5rem .75rem;border:1px solid #ccc;border-radius:.5rem;text-decoration:none;">
      ✎ Modifier
    </a> --}}

    {{-- Bouton supprimer --}}
    <form method="POST"
          action="{{ route('books.destroy', $book) }}"
          onsubmit="return confirm('Supprimer définitivement « {{ $book->title }} » ?');">
      @csrf
      @method('DELETE')
      <button type="submit"
              style="padding:.5rem .75rem;border:1px solid #b91c1c;background:#ef4444;color:white;border-radius:.5rem;">
        Supprimer
      </button>
    </form>
  </div>
@endsection
