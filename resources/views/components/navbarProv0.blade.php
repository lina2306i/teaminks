{{-- resources/views/layouts/partials/navbar.blade.php --}}

<nav class="navbar navbar-expand-md bg-gray-900 shadow-lg border-bottom border-gray-700 sticky-top">
    <div class="container-fluid px-4">

        {{-- Bouton Menu Mobile pour Sidebar --}}
        <button class="btn btn-outline-light d-lg-none me-3"
                type="button"
                id="sidebarToggle"
                aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Logo + Brand --}}
        <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ url('/') }}">
            <img src="{{ asset('images/logo5-ssbg.png') }}"
                 alt="Teaminks Logo"
                 class="me-2 logo-bounce"
                 style="height: 50px;">
            <span class="fs-4 fw-bold brand-text d-none d-md-inline">
                Team<span class="text-primary">inks</span>
            </span>
        </a>

        {{-- Bouton hamburger pour menu navbar --}}
        <button class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent"
                aria-controls="navbarContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <i class="fas fa-ellipsis-v text-white"></i>
        </button>

        {{-- Contenu navbar --}}
        <div class="collapse navbar-collapse" id="navbarContent">

            {{-- Barre de recherche (optionnelle) --}}
            <div class="d-none d-lg-flex mx-auto" style="max-width: 500px; width: 100%;">
                <div class="input-group">
                    <span class="input-group-text bg-gray-800 border-gray-700 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           class="form-control bg-gray-800 border-gray-700 text-white"
                           placeholder="Rechercher des projets, tâches..."
                           id="globalSearch">
                </div>
            </div>

            {{-- Menu à droite --}}
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                {{-- Lien Home --}}
                <li class="nav-item">
                    <a class="nav-link text-white px-3 py-2 rounded-lg
                              {{ request()->routeIs('home') ? 'bg-gray-700' : 'nav-hover' }}"
                       href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>
                        <span class="d-none d-md-inline">Home</span>
                    </a>
                </li>

                @guest
                    {{-- Utilisateur non connecté --}}
                    @if (Route::has('login.form'))
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2 rounded-lg nav-hover"
                               href="{{ route('login.form') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Login
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register.form'))
                        <li class="nav-item">
                            <a class="btn btn-primary px-4 py-2 rounded-pill"
                               href="{{ route('register.form') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                Register
                            </a>
                        </li>
                    @endif
                @else
                    {{-- Utilisateur connecté --}}

                    {{-- Notifications --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white position-relative px-3"
                           href="#"
                           id="notificationDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="fas fa-bell fs-5"></i>
                            @php
                                // $ unreadCount = auth()->user()->unreadNotifications->count()?? 0;

                                // Gestion sécurisée des notifications
                                try {
                                    $unreadCount = auth()->user()->notifications()
                                        ->where('read', false) // ou false selon le type de colonne
                                        ->count();
                                } catch (\Exception $e) {
                                    $unreadCount = 0;
                                }
                            @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700 shadow-lg"
                             style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header d-flex justify-content-between align-items-center border-bottom border-gray-700 pb-2">
                                <span class="text-white fw-bold">Notifications</span>
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @endif
                            </div>

                            @if($unreadCount > 0)
                                @php
                                    try {
                                        $unreadNotifications = auth()->user()->notifications()
                                                        ->where('read', false)
                                                        ->orderBy('created_at', 'desc')
                                                        ->limit(5)
                                                        ->get();
                                    } catch (\Exception $e) {
                                        $unreadNotifications = collect();
                                    }
                                @endphp
                                @foreach($unreadNotifications as $notification)
                                    <a class="dropdown-item text-white py-3 border-bottom border-gray-700"
                                       href="{{ route('leader.notifications') }}">
                                        <div class="d-flex">
                                            <i class="fas fa-circle text-primary me-2 mt-1" style="font-size: 8px;"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                                <small class="text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                <a class="dropdown-item text-center text-primary py-2"
                                   href="{{ route('leader.notifications') }}">
                                    See more
                                </a>
                            @else
                                <div class="dropdown-item text-center text-gray-400 py-4">
                                    <i class="fas fa-bell-slash fs-3 mb-2"></i>
                                    <p class="mb-0">No notification</p>
                                </div>
                            @endif
                        </div>
                    </li>

                    {{-- Messages (optionnel) --}}
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 position-relative" href="#">
                            <i class="fas fa-envelope fs-5"></i>
                            {{-- Décommenter si vous avez un système de messages
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                3
                            </span>
                            --}}
                        </a>
                    </li>

                    {{-- Divider --}}
                    <li class="nav-item d-none d-md-block">
                        <div class="vr bg-gray-700" style="width: 2px; height: 30px;"></div>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white px-3"
                           href="#"
                           id="userDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <img src="{{ Auth::user()->profile ?? asset('images/logo5.png') }}"
                                 alt="Profile"
                                 class="rounded-circle border border-2 border-primary"
                                 style="width: 35px; height: 35px; object-fit: cover;">
                            <span class="d-none d-lg-inline fw-semibold">{{ Str::limit(Auth::user()->name, 12) }}</span>
                        </a>

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

{{-- Formulaire caché pour logout --}}
@auth
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endauth

{{-- Styles personnalisés --}}
<style>
    /* Variables
    :root {
        --primary-color: #3b82f6;
        --gray-900: #111827;
        --gray-800: #1f2937;
        --gray-700: #374151;
        --gray-600: #4b5563;
        --gray-400: #9ca3af;
    }
 */

    /* Navbar Base */
    .navbar {
        backdrop-filter: blur(10px);
        background: linear-gradient(90deg, var(--gray-900) 0%, #1a1f2e 100%) !important;
    }

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
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        border-color: var(--primary-color) !important;
    }

    /* Badge Animation */
    .badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Notification Dropdown Scroll */
    .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-menu::-webkit-scrollbar-track {
        background: var(--gray-800);
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background: var(--gray-600);
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

    /* Dark Mode Colors
    .bg-gray-900 { background-color: var(--gray-900) !important; }
    .bg-gray-800 { background-color: var(--gray-800) !important; }
    .bg-gray-700 { background-color: var(--gray-700) !important; }
    .border-gray-700 { border-color: var(--gray-700) !important; }
    .border-gray-600 { border-color: var(--gray-600) !important; }
    .text-gray-400 { color: var(--gray-400) !important; }*/
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
