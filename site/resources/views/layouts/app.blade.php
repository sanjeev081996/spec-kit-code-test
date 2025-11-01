<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @includeIf('partials.meta')
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/js/app.js','resources/css/app.css'])
  </head>
  <body>
    @includeIf('partials.header')
    <main class="container py-4">
      @yield('content')
    </main>
    @includeIf('partials.footer')
  </body>
  </html>

