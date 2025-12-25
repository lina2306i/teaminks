<!DOCTYPE html>
<!--html lang="en" class="h-full"-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
    <!-- Google Fonts (Nunito) -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <!-- Styles CSS personnalisés -->
    <link href="{{ asset('css/styleAppW.css') }}" rel="stylesheet">
    <!-- Posts CSS (seulement si tu veux le garder séparé) -->
    <link rel="stylesheet" href="{{ asset('css/stylePosts.css') }}">
    <!-- Styles spécifiques aux pages (via @ push) -->
    @stack('styles')

</head>
<body class="h-full bg-gray-950 text-white d-flex flex-column">
        <!-- Navbar  -->
        @include('components.navbar2')

        <!-- Layout principal : Sidebar + Contenu -->
        <div class="d-flex flex-grow-1">
            <!-- Sidebar latérale (fixe à gauche)  -->
            @include('components.sidebar')
              <!-- Main Content Area -->
            <main class="py-4 flex-grow-1 overflow-auto" style="margin-left: 25vw; min-width: 0;">
                <div class="p-4 p-md-5">
                    @yield('contentW')
                </div>
            </main>
        </div>





   <!--main class="py-4">
            @ yield(section: 'contentW')
    </!--main-->

    <!-- Footer -->
    <footer id="contact" class="mt-auto py-4 bg-gray-900 border-top border-gray-800">
        <div class="container text-center">
            <div class="mb-3">
                <a href="https://github.com/lina2306i" target="_blank" class="text-white mx-3 fs-3 hover:text-info transition">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/linalabiadh" target="_blank" class="text-white mx-3 fs-3 hover:text-info transition">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
            <p class="text-gray-400 mb-0">&copy; 2026 - Teaminks. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>
