<nav class="navbar navbar-expand-md bg-gray-900 shadow-lg border-bottom border-gray-700">
    <div class="container">
        <!-- Logo + Nom de l'app -->
        <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ url('home') }}">
            <img src="{{ asset('images/logo-v0.7.1.jpg') }}" alt="Team logo" class="me-2" style="height: 38px;">
            <span class="fs-4 d-none d-md-block fw-bold">Teaminks</span>
        </a>

        <!-- Bouton hamburger pour mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenu du menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Liens à gauche (vide pour l'instant, tu peux ajouter plus tard) -->
            <ul class="navbar-nav me-auto">
                <!-- Exemple : tu pourras ajouter d'autres liens ici plus tard -->
            </ul>

            <!-- Liens à droite -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Lien Home toujours visible -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 py-2 rounded {{ request()->routeIs('home') ? 'bg-gray-700' : 'hover:bg-gray-700' }}
                        transition duration-200" href="{{ route('home') }}">
                        Home
                    </a>
                </li>
                <!-- Si l'utilisateur n'est PAS connecté -->
                @guest
                    @if (Route::has('login.form'))
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2 rounded {{ request()->routeIs('login') ? 'bg-gray-700' : 'hover:bg-gray-700' }}
                                transition duration-200 " href="{{ route('login.form') }}">
                                Login
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register.form'))
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2 rounded {{ request()->routeIs('register') ? 'bg-gray-700' : 'hover:bg-gray-700' }}
                                transition duration-200" href="{{ route('register.form') }}">
                                Register
                            </a>
                        </li>
                    @endif
                @else
                    <!-- Si l'utilisateur EST connecté -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-white px-3" href="#" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700" aria-labelledby="navbarDropdown">
                            <!-- Tu peux ajouter d'autres liens ici (profil, dashboard, etc.) -->
                            <a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2" href="{{ route('profile') }}">
                                Profil
                            </a>
                            <div class="dropdown-divider bg-gray-600"></div>
                            <a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Formulaire caché pour logout -->
@auth
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endauth

<!-- Styles personnalisés pour le hover et couleurs sombres -->
<style>
    .bg-gray-900 { background-color: #111827; }
    .bg-gray-800 { background-color: #1f2937; }
    .bg-gray-700 { background-color: #374151; }
    .border-gray-700 { border-color: #374151; }
    .border-gray-600 { border-color: #4b5563; }

    .nav-link.hover\:bg-gray-700:hover {
        background-color: #374151 !important;
        border-radius: 0.375rem;
    }

    .dropdown-item.hover\:bg-gray-700:hover {
        background-color: #374151;
    }

    .dropdown-divider {
        height: 1px;
        margin: 0.5rem 0;
        background-color: #4b5563;
    }
</style>
