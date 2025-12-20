<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Teaminks - Gestion d\'équipe puissante') }}</title>

    <!--title>Teaminks - Gestion d'équipe puissante</!--title-->

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <!-- added by me -->
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <!-- Styles -->
    <link href="{{ asset('css/styleAppW.css') }}" rel="stylesheet">
    <style> </style>
</head>

<body>

    <!-- Navbar  -->
    @include('components.navbar2')

   <main class="py-4">
            @yield('contentW')
    </main>

    <!-- Footer -->
    <footer id="contact" class="mt-auto">
        <div class="container text-center">
            <div class="mb-3">
                <a href="https://github.com/lina2306i" target="_blank" class="text-white mx-3 fs-3 hover:text-info transition">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/linalabiadh" target="_blank" class="text-white mx-3 fs-3 hover:text-info transition">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
            <p class="text-gray-400 mb-0">&copy; 2025 Team Link. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
