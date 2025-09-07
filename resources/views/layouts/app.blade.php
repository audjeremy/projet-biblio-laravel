<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Bibliothèque')</title>

  <!-- Bootstrap CSS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <nav class="mb-4 d-flex gap-2">
    <a href="{{ route('books.index') }}" class="btn btn-outline-primary">Accueil</a>
    <a href="{{ route('books.news') }}" class="btn btn-outline-primary">Nouveautés</a>
    <a href="{{ route('contact') }}" class="btn btn-outline-primary">Contact</a>
    <a href="{{ route('messages.index') }}" class="btn btn-outline-primary">Messages</a>
  </nav>

  <main>@yield('content')</main>

  <!-- Bootstrap JS (CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
