{{-- resources/views/layouts/navbar.blade.php --}}

<nav class="navbar navbar-expand-lg bg-gray-900 shadow-lg border-bottom border-gray-700 fixed-top">
    <div class="container-fluid px-4">
        <!-- Logo + Nom de l'app -->
        <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('images/logo5-ssbg.png') }}" alt="Team logo" class="me-2" style="height: 50px;">
            <span class="fs-4 d-none d-md-inline fw-bold">Teaminks</span>
        </a>

        <!-- Bouton hamburger pour mobile -->
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars fs-4"></i>
        </button>

        <!-- Contenu du menu -->
        <div class="collapse navbar-collapse" id="navbarContent">
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

            <!-- Liens à droite -->
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <!-- Si non connecté -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 py-2 rounded {{ request()->routeIs('login.form') ? 'bg-gray-700' : '' }}
                            hover-effect" href="{{ route('login.form') }}">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary px-4 py-2 ms-2" href="{{ route('register.form') }}">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </li>
                @else
                    <!-- Notifications -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link text-white position-relative" href="#" id="notificationsDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
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
                    </li>

                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown">
                        <a id="userDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile ?? asset('images/logo5.png') }}"
                                 alt="Profile" class="rounded-circle me-2"
                                 style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #3b82f6;">
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700 shadow-lg"
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

<!-- Styles personnalisés -->
<style>
    /* Couleurs de base */
    .bg-gray-900 { background-color: #111827; }
    .bg-gray-800 { background-color: #1f2937; }
    .bg-gray-700 { background-color: #374151; }
    .border-gray-700 { border-color: #374151; }
    .text-gray-400 { color: #9ca3af; }

    /* Espacement pour éviter que le contenu passe sous la navbar fixe */
    body {
        padding-top: 70px;
    }

    /* Effet hover pour les liens */
    .hover-effect {
        transition: all 0.3s ease;
    }

    .hover-effect:hover {
        background-color: #374151 !important;
        transform: translateY(-2px);
    }

    /* Effet hover pour dropdown items */
    .hover-dropdown {
        transition: all 0.2s ease;
        padding: 10px 20px;
    }

    .hover-dropdown:hover {
        background-color: #374151;
        padding-left: 25px;
    }

    /* Style du toggler sur mobile */
    .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
    }

    /* Animation du menu mobile */
    @media (max-width: 991px) {
        .navbar-collapse {
            background-color: #1f2937;
            margin-top: 1rem;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .navbar-nav .nav-item {
            margin-bottom: 0.5rem;
        }
    }

    /* Style des dropdowns */
    .dropdown-menu {
        border: none;
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

    .dropdown-divider {
        height: 1px;
        margin: 0.5rem 0;
        background-color: #4b5563;
        opacity: 1;
    }

    /* Badge notifications */
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
</style>
