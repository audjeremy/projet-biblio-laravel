<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Bibliothèque')</title>
  @vite(['resources/sass/app.scss','resources/js/app.js'])
</head>
<body>
  <nav>
    <a href="{{ route('books.index') }}">Accueil</a> |
    <a href="{{ route('books.news') }}">Nouveautés</a> |
    <a href="/contact">Contact</a> |
    <a href="/messages">Messages</a>
  </nav>

  <main>
    @yield('content')
  </main>
</body>
</html>
