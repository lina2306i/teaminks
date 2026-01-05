<nav class="navbar navbar-expand-md bg-gray-900 shadow-lg border-bottom border-gray-700">
    <div class="container">
        <!-- Logo + Nom de l'app -->
        {{-- Logo + Brand
        <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ url('home') }}">
            <img src="{{ asset('images/logo5-ssbg.png') }}" alt="Team logo" class="me-2 logo-bounce" style="height: 65px;">
            <span class="fs-4 d-none d-md-block fw-bold">Teaminks</span>
        </a>
        --}}
        <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ url('/win') }}">
            <img src="{{ asset('images/logo5-ssbg.png') }}"
                 alt="Teaminks Logo"
                 class="me-2 logo-bounce"
                 style="height: 50px;">
            <span class="fs-4 fw-bold brand-text d-none d-md-inline">
                Team<span class="text-primary">inks</span>
            </span>
        </a>
        {{-- Bouton Menu Mobile pour Sidebar --}}
        <!-- Bouton hamburger pour mobile     <span class="navbar-toggler-icon"></span> -->
        <button class=" border-0 btn-outline-light d-lg-none me-3" type="button" id="sidebarToggle"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Contenu du menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Liens principaux (centre) -->
            <ul class="navbar-nav mx-auto d-none d-lg-flex">
                <li class="nav-item">
                    <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('home') ? 'bg-primary' : '' }}
                        hover-effect" href="{{ route('home') }}">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('leader.dashboard') ? 'bg-primary' : '' }}
                            hover-effect" href="{{ route('leader.dashboard') }}">
                            <i class="fas fa-chart-line me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('leader.tasks.*') ? 'bg-primary' : '' }}
                            hover-effect" href="{{ route('leader.tasks.index') }}">
                            <i class="fas fa-tasks me-2"></i>Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('leader.projects.*') ? 'bg-primary' : '' }}
                            hover-effect" href="{{ route('leader.projects.index') }}">
                            <i class="fas fa-project-diagram me-2"></i>Projects
                        </a>
                    </li>
                @endauth
            </ul>
            <!-- Liens à gauche (vide pour l'instant, tu peux ajouter plus tard) -->
            <ul class="navbar-nav me-auto">
                <!-- Exemple : tu pourras ajouter d'autres liens ici plus tard -->
            </ul>

            {{-- Barre de recherche (optionnelle) --}}
            <div class="d-none d-lg-flex mx-auto" style="max-width: 500px; width: 100%;">
                <div class="input-group">
                    <span class="input-group-text   border-gray-700 text-gray-400">
                        <i class="fas fa-search me-2"></i>
                    </span>
                    <input type="text"
                           class="form-control   border-gray-700 text-white"
                           placeholder="Search : projects, tasks..."
                           id="globalSearch">
                </div>
            </div>
            <!-- Liens à droite align-items-lg-center  -->
            <ul class="navbar-nav ms-auto align-items-center  gap-2">
                {{-- Lien Home toujours visible
                <li class="nav-item">
                    <a class="nav-link text-white px-3 py-2 rounded-lg
                        {{ request()->routeIs('home') ? 'bg-gray-700' : 'hover:bg-gray-700' }}
                        transition duration-200" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i> <span class="d-none d-md-inline">Home</span>
                    </a>
                </li>  --}}
                {{-- Utilisateur non connecté --}}
                @guest
                    @if (Route::has('login.form'))
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2 rounded-lg nav-hover
                                {{ request()->routeIs('login') ? 'bg-gray-700' : 'hover:bg-gray-700' }}
                                transition duration-200 " href="{{ route('login.form') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    @endif
                    @if (Route::has('register.form'))
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2 rounded-pill
                                 {{ request()->routeIs('register') ? 'bg-gray-700' : 'hover:bg-gray-700' }}
                                transition duration-200" href="{{ route('register.form') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    @endif
                @else
                    {{-- Si l'utilisateur EST connecté
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-white px-3" href="#" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700" aria-labelledby="navbarDropdown">
                            <!-- Tu peux ajouter d'autres liens ici (profil, dashboard, etc.) -->
                            <a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2" href="{{ route('leader.profile') }}">
                                Profil
                            </a>
                            <a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2" href="{{ route('leader.dashboard') }}">
                                Dashboard |{{ Auth::user()->role }}
                            </a>
                            <!--a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    { { Auth::user()->name }}
                            </!--a-->
                            <div class="dropdown-divider bg-gray-600">
                                 <a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                </form>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                    --}}

                    {{-- Notifications v--}}
                    <li class="nav-item  rounded-pill dropdown me-3">
                        <a href="{{ route('leader.notifications') }}"
                        class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 position-relative
                                {{ request()->routeIs('leader.notifications') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                            <i class="fas fa-bell fs-5"></i>
                            <span class="nav-text">Notifications</span>
                            {{-- Badge de notification --}}
                            @php
                                $unreadCount = auth()->user()->notifications()
                                    ->where('read', false)
                                    ->count();
                                //$unreadCount = auth()->user()->unreadNotifications->count() ?? 0;
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
                            @endif
                            @if(request()->routeIs('leader.notifications'))
                                <i class="fas fa-chevron-right ms-auto text-primary"></i>
                            @endif
                        </a>

                    </li>
                     {{-- Notifications
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link text-white position-relative" href="{{ route('leader.notifications') }}" id="notificationsDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fs-5" href="{{ route('leader.notifications') }}" ></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                4+
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700 shadow-lg"
                            aria-labelledby="notificationsDropdown" style="width: 320px;">
                            <li class="dropdown-header text-white fw-bold border-bottom border-gray-700 pb-2">
                                Notifications
                            </li>
                            <li>
                                <a class="dropdown-item text-white hover-dropdown py-3" href="#">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle text-primary me-3 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">New task assigned</div>
                                            <small class="text-gray-400">5 minutes ago</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider bg-gray-700"></li>
                            <li class="text-center">
                                <a class="dropdown-item text-primary" href="{{ route('leader.notifications') }}">
                                    View all notifications
                                </a>
                            </li>
                        </ul>
                    </li>--}}

                    {{-- Messages  V (optionnel)
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 position-relative" href="#">
                            <i class="fas fa-envelope fs-5"></i>
                            {{-- Décommenter si vous avez un système de messages --} }
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                3
                            </span>
                        </a>
                    </li> --}}

                    {{-- Divider
                    <li class="nav-item d-none d-md-block">
                        <div class="vr bg-gray-700" style="width: 2px; height: 30px;"></div>
                    </li>
                    --}}

                    <!-- Profile Dropdown v -->
                    <li class="nav-item dropdown">
                        <a id="userDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile ?? asset('images/logo5.png') }}"
                                 alt="Profile" class="rounded-circle me-2"  class="me-2 logo-bounce"
                                 style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #3b82f6;">
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end  border-gray-700 shadow-lg"
                            aria-labelledby="userDropdown">

                            <li class="dropdown-header text-white">
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <small class="text-gray-400">{{ Auth::user()->email }}</small>
                            </li>
                            <li><hr class="dropdown-divider bg-gray-700"></li>
                            <li>
                                <a class="dropdown-item text-white hover-dropdown" href="{{ route('leader.profile') }}">
                                    <i class="fas fa-user me-2"></i>My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-white hover-dropdown" href="{{ route('leader.dashboard') }}">
                                    <i class="fas fa-chart-line me-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-white hover-dropdown" href="#">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider bg-gray-700"></li>
                            <li>
                                <a class="dropdown-item text-danger hover-dropdown" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>

                         <div class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700 shadow-lg p-0"
                             style="min-width: 250px;">

                            {{-- User Info Header --}}
                            <div class="dropdown-header bg-gray-900 border-bottom border-gray-700 p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ Auth::user()->profile ?? asset('images/logo5.png') }}"
                                         alt="Profile"
                                         class="rounded-circle border border-2 border-primary"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <div class="text-white fw-bold">{{ Auth::user()->name }}</div>
                                        <small class="text-gray-400">{{ Auth::user()->email }}</small>
                                        <div>
                                            <span class="badge bg-primary mt-1">{{ ucfirst(Auth::user()->role) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Menu Items --}}
                            <div class="py-2">
                                <a class="dropdown-item text-white d-flex align-items-center gap-3 px-4 py-2 dropdown-hover"
                                   href="{{ route('leader.profile') }}">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Mon Profil</span>
                                </a>

                                <a class="dropdown-item text-white d-flex align-items-center gap-3 px-4 py-2 dropdown-hover"
                                   href="{{ route('leader.dashboard') }}">
                                    <i class="fas fa-th-large"></i>
                                    <span>Dashboard</span>
                                </a>

                                <a class="dropdown-item text-white d-flex align-items-center gap-3 px-4 py-2 dropdown-hover"
                                   href="#">
                                    <i class="fas fa-cog"></i>
                                    <span>Paramters</span>
                                </a>

                                <a class="dropdown-item text-white d-flex align-items-center gap-3 px-4 py-2 dropdown-hover"
                                   href="#">
                                    <i class="fas fa-question-circle"></i>
                                    <span>Help & Support</span>
                                </a>
                            </div>

                            <div class="dropdown-divider bg-gray-700 my-0"></div>

                            {{-- Logout --}}
                            <div class="p-2">
                                <a class="dropdown-item text-danger d-flex align-items-center gap-3 px-4 py-2 dropdown-hover"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
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

    /* Logo Animation */
    .logo-bounce {
        animation: bounce 2s infinite;
        transition: transform 0.3s ease;
    }

    .logo-bounce:hover {
        transform: scale(1.1) rotate(5deg);
        animation: none;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    /* Logo Animation */
    .logo-img {
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        transform: rotate(360deg) scale(1.1);
    }

    /* Badge Animation */
    .badge {
        animation: pulse 2s infinite;
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

       /* Nav Links Hover */
    .nav-hover {
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-hover::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: 80%;
        height: 2px;
        background: var(--primary-color);
        transition: transform 0.3s ease;
    }

    .nav-hover:hover {
        background-color: var(--gray-700) !important;
        transform: translateY(-2px);
    }

    .nav-hover:hover::after {
        transform: translateX(-50%) scaleX(1);
    }


    .bg-gray-900 { background-color: #111827; }
    .bg-gray-800 { background-color: #1f2937; }
    .bg-gray-700 { background-color: #374151; }
    .border-gray-700 { border-color: #374151; }
    .border-gray-600 { border-color: #4b5563; }

     .navbar {
        backdrop-filter: blur(10px);
        /* !!!         background: linear-gradient(90deg, var(--gray-900) 50%, #8ba9fd 100%) !important;
 */
    }

    .nav-link.hover\:bg-gray-700:hover {
        background-color: #859dc4e1 !important;
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

     /* Dropdown Styling */
    .dropdown-menu {
        border-radius: 12px;
        border: 1px solid var(--gray-700);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-hover {
        transition: all 0.2s ease;
    }

    .dropdown-hover:hover {
        background-color: var(--gray-700) !important;
        padding-left: 1.75rem !important;
    }

    /* Search Bar */
    #globalSearch {
        transition: all 0.3s ease;
    }

    #globalSearch:focus {
        box-shadow: 0 0 0 3px rgba(149, 187, 248, 0.807);
        border-color: #3b82f6 !important;
    }

    /* Notification Dropdown Scroll */
    .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-menu::-webkit-scrollbar-track {
        background: var(--gray-700);
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 3px;
    }
    /* Mobile Adjustments */
    @media (max-width: 767.98px) {
        .navbar-brand .brand-text {
            font-size: 1rem;
        }

        .logo-bounce {
            height: 40px !important;
        }
    }

</style>


{{-- JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Search functionality (exemple)
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // Ajoutez ici votre logique de recherche
                console.log('Recherche:', searchTerm);
            });
        }
    });
</script>
