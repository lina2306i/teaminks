<nav class="navbar navbar-expand-md bg-gray-900 shadow-lg border-bottom border-gray-700">
    <div class="container">
        <!-- Logo + Nom de l'app -->
        <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ url('home') }}">
            <img src="{{ asset('images/logo5-ssbg.png') }}" alt="Team logo" class="me-2" style="height: 65px;">
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
                 <div class="collapse navbar-collapse" id="navbarContent">

                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('home') ? 'bg-primary' : '' }}"
                        href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Home
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
                    <!-- Liens centrés (desktop seulement) -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 d-none d-lg-flex">

                        @auth
                        <li class="nav-item">
                            <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('leader.dashboard') ? 'bg-primary' : '' }}"
                            href="{{ route('leader.dashboard') }}">
                                <i class="fas fa-chart-line me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('leader.tasks.*') ? 'bg-primary' : '' }}"
                            href="{{ route('leader.tasks.index') }}">
                                <i class="fas fa-tasks me-2"></i>Tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white px-4 py-2 rounded-pill {{ request()->routeIs('leader.projects.*') ? 'bg-primary' : '' }}"
                            href="{{ route('leader.projects.index') }}">
                                <i class="fas fa-project-diagram me-2"></i>Projects
                            </a>
                        </li>
                        @endauth
                    </ul>
                    <!-- Global Search Bar (visible on md and larger screens) -->
                    <div class="d-none d-flex align-items-center gap-3 d-lg-block" style="min-width: 300px;">
                        <div class="input-group">
                            <span class="input-group-text bg-gray-400 border-gray-700 text-gray-400">
                                <i class="fas fa-search me-2"></i>
                            </span>
                            <input type="text" class="form-control bg-gray-800 border-gray-700 text-white"
                                placeholder="Search projects, tasks..." id="globalSearch">
                        </div>
                    </div>
                    <!-- Notification Bell -->
                    @auth
                        <a href="{{ route('leader.notifications') }}"
                        class="position-relative text-white nav-link p-2">
                            <i class="fas fa-bell fs-5"></i>
                            @php
                                $unreadCount = auth()->user()->notifications()->where('read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @endauth

                    <!-- Profil Dropdown -->
                    @auth
                        <div class="dropdown nav-item">
                            <a class="nav-link d-flex align-items-center text-white text-decoration-none px-3 dropdown-toggle"
                            id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ Auth::user()->profile ?? asset('images/logo5.png') }}"
                                    alt="Profile" class="rounded-circle border border-2 border-primary"
                                    style="width: 38px; height: 38px; object-fit: cover;">
                                <span class="ms-2 d-none d-xl-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-400 shadow-lg" style="min-width: 260px;">
                                <li class="dropdown-header   px-3 py-2 border-bottom border-gray-700">
                                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                                    <small class="text-gray-400">{{ Auth::user()->email }}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                                    </div>
                                </li>
                                <!-- Tu peux ajouter d'autres liens ici (profil, dashboard, etc.) -->
                                <li><a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2" href="{{ route('leader.profile') }}"><i class="fas fa-user me-2"></i> My Profil</a></li>
                                <li><a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2" href="{{ route('leader.dashboard') }}"><i class="fas fa-th-large me-2"></i> Dashboard</a></li>
                                <li><a class="dropdown-item text-white hover:bg-gray-700 px-4 py-2" href="#"><i class="fas fa-cog me-2"></i> Parameters</a></li>
                                <li><hr class="dropdown-divider bg-gray-700"></li>
                                <li>
                                    <a class="dropdown-item text-danger hover:bg-gray-700 px-4 py-2" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout- {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login.form') }}" class="btn btn-outline-light me-2">Login</a>
                        <a href="{{ route('register.form') }}" class="btn btn-primary">Register</a>
                    @endauth
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
