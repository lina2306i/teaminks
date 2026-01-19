<!-- Sidebar -->
<aside class="bg-gray-950 border-end border-gray-800 vh-100 position-fixed d-flex flex-column transition-all"
       style="width: 280px; z-index: 1025; top: 0; left: 0;" id="sidebar">
    <div class="d-flex flex-column h-100 justify-content-between">

        <!-- Logo + App Name -->
        <div class="p-4 border-bottom border-gray-700">
            <a href="{{ route('leader.dashboard') }}"
               class="d-flex align-items-center gap-3 logo-img  text-white text-decoration-none">
                <img src="{{ asset('images/logo5.png') }}" alt="Teamink Logo"
                     class="img-fluid" style="height: 40px; border-radius: 50px; border: 2px solid darkblue;">
                <h2 class="fs-5 fw-bold mb-0 d-none d-md-block">Teamink</h2>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow-1 py-4 px-3 overflow-y-auto">
            <ul class="nav flex-column gap-2">
                @php
                    $menuItems = [
                        ['route' => 'leader.dashboard', 'icon' => 'fa-th-large', 'text' => 'Dashboard'],
                        ['route' => 'leader.team.index', 'icon' => 'fa-users', 'text' => 'Team'],
                        ['route' => 'leader.projects.index', 'icon' => 'fa-project-diagram', 'text' => 'Projects'],
                        ['route' => 'leader.tasks.index', 'icon' => 'fa-clipboard-check', 'text' => 'Tasks'],
                        ['route' => 'leader.posts.index', 'icon' => 'fa-newspaper', 'text' => 'Posts'],
                        ['route' => 'leader.interface.calendar', 'icon' => 'fa-calendar-alt', 'text' => 'Calendar'],
                        ['route' => 'leader.folders', 'icon' => 'fa-folder-closed', 'text' => 'Folders'],
                        ['route' => 'leader.notes', 'icon' => 'fa-note-sticky', 'text' => 'Notes'],
                        ['route' => 'leader.notifications', 'icon' => 'fa-bell', 'text' => 'Notifications'],
                    ];
                @endphp

                @foreach($menuItems as $item)
                    @if(Route::has($item['route']))
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}"
                           class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                                  {{ request()->routeIs(str_replace('.*', '*', $item['route'])) ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }}
                                  transition-all">
                            <i class="fas {{ $item['icon'] }} fs-5"></i>
                            <span class="sidebar-text d-none d-md-block">{{ $item['text'] }}</span>
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <!-- User Profile Footer -->
        <div class="p-3 border-top border-gray-700">
            @auth
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('leader.profile') }}"
                   class="d-flex align-items-center gap-3 text-decoration-none text-white flex-grow-1">
                    <img src="{{ auth()->user()->profile ?? asset('images/user-default.jpg') }}"
                         alt="Profile"
                         class="rounded-circle border logo-img  border-gray-600"
                         style="width: 40px; height: 40px; object-fit: cover;">
                    <div class="d-none d-md-block">
                        <div class="fw-semibold small">{{ auth()->user()->name }}{{-- Str::limit(auth()->user()->name, 15) --}}</div>
                        <small class="text-gray-400" style="font-size: 0.75rem;">{{ auth()->user()->email }}{{-- Str::limit(auth()->user()->email, 20) --}}</small>
                     </div>
                </a>
                <!-- Bouton Logout -->
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm rounded-circle p-2"
                            title="Logout"
                            onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</aside>

<!-- Overlay pour mobile -->
<div class="overlay" id="sidebarOverlay" style="display: none;"></div>

@push('script')
   <script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const navbarToggleMobile = document.getElementById('sidebarToggleMobile');
    const sidebarToggleFloat = document.getElementById('sidebarToggleFloat');
    let isTabletOrMobile = false;

    // Fonction pour vérifier la taille d'écran
    function checkScreenSize() {
        const width = window.innerWidth;

        // Desktop (> 992px)
        if (width > 992) {
            sidebar.style.width = '280px';
            sidebar.style.transform = 'translateX(0)';
            overlay.style.display = 'none';
            document.querySelector('main').style.marginLeft = '280px';
            isTabletOrMobile = false;

            // Afficher les textes
            document.querySelectorAll('.sidebar-text').forEach(el => {
                el.classList.remove('d-none');
            });
        }
        // Tablette (768-992px)
        else if (width >= 768 && width <= 992) {
            sidebar.style.width = '80px';
            sidebar.style.transform = 'translateX(0)';
            overlay.style.display = 'none';
            document.querySelector('main').style.marginLeft = '80px';
            isTabletOrMobile = true;

            // Cacher les textes, montrer icônes seulement
            document.querySelectorAll('.sidebar-text').forEach(el => {
                el.classList.add('d-none');
            });
        }
        // Mobile (< 768px)
        else {
            sidebar.style.width = '280px';
            sidebar.style.transform = 'translateX(-100%)';
            document.querySelector('main').style.marginLeft = '0';
            isTabletOrMobile = true;

            // Montrer les textes pour mobile quand sidebar ouverte
            document.querySelectorAll('.sidebar-text').forEach(el => {
                el.classList.remove('d-none');
            });
        }
    }

    // Fonction pour toggle sidebar
    function toggleSidebar() {
        if (window.innerWidth < 768) {
            // Mobile: sidebar glissante
            if (sidebar.style.transform === 'translateX(-100%)' || sidebar.style.transform === '') {
                sidebar.style.transform = 'translateX(0)';
                overlay.style.display = 'block';
            } else {
                sidebar.style.transform = 'translateX(-100%)';
                overlay.style.display = 'none';
            }
        } else if (window.innerWidth <= 992) {
            // Tablette: toggle entre 80px et 280px
            if (sidebar.style.width === '80px') {
                sidebar.style.width = '280px';
                document.querySelectorAll('.sidebar-text').forEach(el => {
                    el.classList.remove('d-none');
                });
            } else {
                sidebar.style.width = '80px';
                document.querySelectorAll('.sidebar-text').forEach(el => {
                    el.classList.add('d-none');
                });
            }
        }
    }

    // Événements
    navbarToggleMobile?.addEventListener('click', toggleSidebar);
    sidebarToggleFloat?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);

    // Fermer sidebar en cliquant en dehors (mobile)
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 &&
            !sidebar.contains(event.target) &&
            !navbarToggleMobile.contains(event.target) &&
            !sidebarToggleFloat.contains(event.target) &&
            sidebar.style.transform === 'translateX(0)') {
            toggleSidebar();
        }
    });

    // Vérifier la taille d'écran au chargement et au redimensionnement
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
});
</script>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.querySelector('.sidebar-overlay');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('active');
        document.body.classList.toggle('sidebar-open');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    });
</script>
@endpush


@push('styles')
<style>
    @media (max-width: 992px) {
        aside#sidebar {
            width: 80px !important;
            min-width: 80px !important;
        }
        .sidebar-text { display: none; }
        .sidebar .nav-link { justify-content: flex-start start;padding-left: 1px; }

        aside#sidebar .d-none.d-lg-inline,
        aside#sidebar .d-none.d-md-block {
            display: none !important;
        }

        main {
            margin-left: 80px !important;
        }

        .navbar-toggler {
            display: block !important;
        }
    }

    /* Logo Animation */
    .logo-img {
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        transform: rotate(360deg) scale(1.1);
    }


     /* Animation au hover */
    .sidebar-link i {
        transition: transform 0.3s ease;
    }

    .sidebar-link:hover i {
        transform: scale(1.1);
    }

    .transition-all {
        transition: all 0.3s ease;
    }
</style>



<style>
/* Styles généraux */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Overlay pour mobile */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1020;
    backdrop-filter: blur(2px);
}

/* Styles responsive */
    @media (max-width: 992px) {
        aside#sidebar {
            width: 80px !important;
            min-width: 80px !important;
        }

        aside#sidebar .sidebar-text,
        aside#sidebar .d-md-block:not(.d-lg-inline) {
            display: none !important;
        }

        main {
            margin-left: 80px !important;
            transition: margin-left 0.3s ease;
        }

        .navbar-toggler {
            display: block !important;
        }
    }

    @media (max-width: 767.98px) {
        aside#sidebar {
            width: 80px !important;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        main {
            margin-left: 0 !important;
            transition: margin-left 0.3s ease;
        }

        .navbar-toggler {
            display: none;
        }

        /* Bouton flottant spécifique mobile */
        #sidebarToggleFloat {
            display: block !important;
        }
    }

    @media (min-width: 993px) {
        /* Cacher le bouton flottant sur desktop */
        #sidebarToggleFloat,
        #sidebarToggleMobile {
            display: none !important;
        }
    }

/* Styles pour les états actifs */
.nav-link.active {
    background-color: #374151 !important;
    color: white !important;
}

/* Scrollbar personnalisée */
aside::-webkit-scrollbar {
    width: 6px;
}

aside::-webkit-scrollbar-track {
    background: #1f2937;
}

aside::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 3px;
}

aside::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
    </style>
@endpush
