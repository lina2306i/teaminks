{{-- resources/views/layouts/sidebar.blade.php --}}

<aside class="sidebar bg-gray-950 border-end border-gray-800 vh-100 position-fixed d-flex flex-column"
       id="sidebar">
    <div class="d-flex flex-column h-100">

        <!-- Logo + App Name -->
        <div class="sidebar-header p-4 border-bottom border-gray-700">
            <a href="{{ route('leader.dashboard') }}"
               class="d-flex align-items-center gap-3 text-white text-decoration-none">
                <img src="{{ asset('images/logo5.png') }}"
                     alt="Teamink Logo"
                     class="sidebar-logo"
                     style="height: 40px; border-radius: 50%; border: 2px solid #3b82f6;">
                <h2 class="fs-5 fw-bold mb-0 sidebar-text">Teaminks</h2>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav flex-grow-1 py-4 px-3 overflow-y-auto">
            <ul class="nav flex-column gap-2">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('leader.dashboard') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large fs-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>

                <!-- Team -->
                <li class="nav-item">
                    <a href="{{ route('leader.team.index') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.team.*') ? 'active' : '' }}">
                        <i class="fas fa-users fs-5"></i>
                        <span class="sidebar-text">Team</span>
                    </a>
                </li>

                <!-- Posts -->
                <li class="nav-item">
                    <a href="{{ route('leader.posts.index') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.posts.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper fs-5"></i>
                        <span class="sidebar-text">Posts</span>
                    </a>
                </li>

                <!-- Projects -->
                <li class="nav-item">
                    <a href="{{ route('leader.projects.index') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram fs-5"></i>
                        <span class="sidebar-text">Projects</span>
                    </a>
                </li>

                <!-- Tasks -->
                <li class="nav-item">
                    <a href="{{ route('leader.tasks.index') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check fs-5"></i>
                        <span class="sidebar-text">Tasks</span>
                    </a>
                </li>

                <!-- Calendar -->
                <li class="nav-item">
                    <a href="{{ route('leader.interface.calendar') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.calendar.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt fs-5"></i>
                        <span class="sidebar-text">Calendar</span>
                    </a>
                </li>

                <!-- Folders -->
                <li class="nav-item">
                    <a href="#"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->is('leader/folders*') ? 'active' : '' }}">
                        <i class="fas fa-folder fs-5"></i>
                        <span class="sidebar-text">Folders</span>
                    </a>
                </li>

                <!-- Notes -->
                <li class="nav-item">
                    <a href="{{ route('leader.notes') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.notes') ? 'active' : '' }}">
                        <i class="fas fa-note-sticky fs-5"></i>
                        <span class="sidebar-text">Notes</span>
                    </a>
                </li>

                <!-- Notifications -->
                <li class="nav-item">
                    <a href="{{ route('leader.notifications') }}"
                       class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('leader.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="sidebar-text">Notifications</span>
                        <span class="badge bg-danger ms-auto">3</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile Footer -->
        @auth
        <div class="sidebar-footer p-4 border-top border-gray-700">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('leader.profile') }}"
                   class="d-flex align-items-center gap-3 text-decoration-none text-white flex-grow-1">
                    <img src="{{ auth()->user()->profile ?? asset('images/logo5.png') }}"
                         alt="Profile"
                         class="rounded-circle border border-gray-600"
                         style="width: 48px; height: 48px; object-fit: cover;">
                    <div class="sidebar-text">
                        <div class="fw-semibold text-truncate" style="max-width: 120px;">
                            {{ auth()->user()->name }}
                        </div>
                        <small class="text-gray-400">{{ Str::limit(auth()->user()->email, 20) }}</small>
                    </div>
                </a>

                <!-- Bouton Logout -->
                <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm rounded-circle p-2"
                            title="Logout"
                            onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</aside>

<!-- Overlay pour fermer la sidebar sur mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Styles -->
<style>
    /* Variables de couleur */
    :root {
        --sidebar-width: 280px;
        --sidebar-collapsed: 80px;
        --bg-dark: #030712;
        --bg-gray-950: #0a0f1e;
        --bg-gray-900: #111827;
        --bg-gray-800: #1f2937;
        --bg-gray-700: #374151;
        --border-gray: #374151;
        --text-gray: #9ca3af;
        --primary: #3b82f6;
    }

    /* Sidebar de base */
    .sidebar {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1040;
        background: linear-gradient(180deg, var(--bg-gray-950) 0%, var(--bg-dark) 100%);
    }

    .sidebar-logo,
    .sidebar-text {
        transition: opacity 0.3s ease;
    }

    /* Liens sidebar */
    .sidebar-link {
        color: var(--text-gray);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .sidebar-link:hover {
        background-color: var(--bg-gray-800);
        color: white;
        transform: translateX(5px);
    }

    .sidebar-link.active {
        background-color: var(--bg-gray-800);
        color: white;
        border-left: 3px solid var(--primary);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }

    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #3b82f6, #8b5cf6);
    }

    /* Badge pour notifications */
    .sidebar-link .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }

    /* Scrollbar personnalisée */
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: var(--bg-gray-950);
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: var(--bg-gray-700);
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: var(--primary);
    }

    /* Overlay pour mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1039;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar-overlay.active {
        display: block;
        opacity: 1;
    }

    /* Ajustement du contenu principal */
    main {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .sidebar {
            width: var(--sidebar-collapsed);
            min-width: var(--sidebar-collapsed);
        }

        .sidebar-text {
            display: none;
        }

        .sidebar-header h2 {
            display: none;
        }

        .sidebar-footer .sidebar-text {
            display: none;
        }

        main {
            margin-left: var(--sidebar-collapsed);
        }

        .sidebar-link {
            justify-content: center;
            padding: 1rem !important;
        }

        .sidebar-link .badge {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            width: var(--sidebar-width);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-text {
            display: block;
        }

        .sidebar-header h2 {
            display: block;
        }

        .sidebar-footer .sidebar-text {
            display: block;
        }

        main {
            margin-left: 0;
        }
    }

    /* Animation au hover */
    .sidebar-link i {
        transition: transform 0.3s ease;
    }

    .sidebar-link:hover i {
        transform: scale(1.1);
    }

    /* Footer profile hover */
    .sidebar-footer a:hover {
        opacity: 0.8;
    }

    /* Bouton toggle pour mobile */
    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1041;
        background: var(--primary);
        color: white;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
    }

    @media (max-width: 768px) {
        .sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>

<!-- Script pour le toggle mobile -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    // Créer le bouton toggle pour mobile
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'sidebar-toggle';
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    document.body.appendChild(toggleBtn);

    // Toggle sidebar sur mobile
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    // Fermer avec l'overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
});
</script>
