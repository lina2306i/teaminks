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
    <!-- FullCalendar (via CDN, pas de build lourd @ 6.1.15)    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!--     @ filepondStyles
   <link rel="stylesheet" href="https://unpkg.com/dropzone@6/dist/dropzone.css">
    Styles CSS personnalisés -->
    <link href="{{ asset('css/styleAppW.css') }}" rel="stylesheet">
    <!-- Posts CSS (seulement si tu veux le garder séparé) -->
    <link rel="stylesheet" href="{{ asset('css/stylePosts.css') }}">
    <!-- Styles spécifiques aux pages (via @ push) -->
    @stack('styles')

</head>
<body class="h-full bg-gray-950 text-white d-flex flex-column">
        <!-- Navbar 2/Pro  -->
        @include('components.navbar2')

        <!-- Layout principal : Sidebar + Contenu -->
        <div class="d-flex flex-grow-1">
            <!-- Sidebar latérale (fixe à gauche) Pro -->
            @include('components.sidebar')
              <!-- Main Content Area 25vw-->
            <main class="py-4 flex-grow-1 overflow-auto" style="margin-top: 56px; margin-left: 22vw; padding: 20px; min-width: 0;">
                <div class="p-4 p-md-5">
                    @yield('contentW')
                </div>
            </main>
            <!--main class="transition-all" style="min-height: calc(100vh - 56px); margin-top: 56px; margin-left: 280px; padding: 20px;">
                @ yield('contentW')
            </!--main-->
        </div>

        {{-- Tailwind ::class="fixed bottom-6 right-6 w-16 h-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center text-2xl hover:bg-blue-600 hover:scale-110 transition-all duration-300 opacity-0 pointer-events-none z-50"
            bootstrap5 :: class="btn btn-primary btn-circle btn-lg shadow-lg d-flex align-items-center justify-content-center opacity-0 pointer-events-none transition-all duration-300"
                          class="btn btn-primary btn-lg rounded-circle shadow-lg d-flex align-items-center justify-content-center
                                    opacity-0 pointer-events-none transition-all duration-300
                                    hover:scale-110 hover:shadow-xl"
        --}}
        <!-- Bouton Retour en haut :: bouton “Retour en haut” élégant, fixe en bas à droite de l’écran, qui apparaît uniquement quand on a scrollé un peu.-->
        <div class="fixed bottom-5 right-5 z-50">
            <button id="backToTop" type="button"
                    class="btn btn-primary rounded-circle shadow-lg
                        translate-y-100 transition-all duration-500 ease-in-out hover:scale-110 hover:shadow-2xl
                        position-fixed bottom-0 end-0 mb-5 me-5 opacity-0 "
                    style="width: 60px; height: 60px; pointer-events: auto;"
                    data-bs-toggle="tooltip"title="Back to top"
                    aria-label="Back to top">
                <i class="fas fa-arrow-up fs-4"></i>
            </button>
        </div>

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
    <!--script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></!--script-->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script>
    <!-- script of Calendrier visuel des deadlines :: FullCalendar (via CDN, pas de build lourd) @ 6.1.15
         <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    -->

    <script src="https://unpkg.com/dropzone@6/dist/dropzone-min.js"></script>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Scroll fluide quand on clique sur un lien d'ancre
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    <!-- @ filepondScripts Script pour le bouton Retour en haut -->
    <script>
        // Sélection du bouton
        document.addEventListener('DOMContentLoaded', function () {
            const backToTopButton = document.getElementById('backToTop');

            if (!backToTopButton) return; // sécurité si l'élément n'existe pas

            // Afficher/masquer le bouton selon le scroll après avoir scrollé de 300px
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTopButton.classList.remove('opacity-0', 'translate-y-100', 'pointer-events-none');
                    backToTopButton.classList.add('opacity-100', 'translate-y-0');
                } else {
                    backToTopButton.classList.remove('opacity-100', 'translate-y-0');
                    backToTopButton.classList.add('opacity-0'), 'translate-y-100', 'pointer-events-none';
                }
            });

            // Scroll fluide vers le haut au clic
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({top: 0, behavior: 'smooth'});
            });
            // Optionnel : activer le tooltip Bootstrap
            const tooltip = new bootstrap.Tooltip(backToTopButton);
        });
    </script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <!-- Optionnel: File type validation -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>

    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>


    @stack('scripts')


</body>
</html>
