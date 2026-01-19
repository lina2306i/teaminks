
<!-- Sidebar -->
        <aside class="sidebar bg-gray-950 border-end border-gray-800 hv-1000 position-fixed d-flex flex-column transition-all"
            style="width: 280px; z-index: 1000;" id="sidebar">
            <div class="d-flex flex-column h-100 ">
                <!-- Logo + App Name -?email <div class="p-4 border-bottom border-gray-700 text-center">
                    <a href="{ { route('leader.dashboard') }}" class="d-flex align-items-center gap-3 text-white text-decoration-none justify-content-center">
                        <img src="{ { asset('images/logo5.png') }}" alt="Teamink Logo" class="img-fluid" style="height: 40px; border-radius: 50px;border: 2px solid darkblue;">
                        <h2 class="fs-5 fw-bold mb-0 d-none d-md-block">Teamink</h2>
                    </a>
                </div> -->
               {{-- Logo & Brand --}}
                <div class="sidebar-header p-4 border-bottom border-gray-600">
                    <a href="{{ route('leader.dashboard') }}"  class="d-flex align-items-center gap-3 text-white text-decoration-none">
                    <img src="{{ asset('images/logo5.png') }}"
                            alt="Teamink Logo"
                            class="logo-img rounded-circle border border-2 border-primary"
                            style="height: 45px; width: 45px; object-fit: cover;">
                        <div class="brand-text d-none d-lg-block">
                            <h6 class="fs fw-bold mb-0 text-primary">Teaminks</h6>
                            <small class="text-gray-400"> Workspace</small>
                        </div>
                    </a>
                </div>


                <!-- Navigation Menu -->
                <nav class="sidebar-nav flex-grow-1 py-4 px-3 overflow-y-auto  ">
                    <ul class="nav flex-column gap-2">


                        <li class="nav-item">
                            <a href="{{ route('leader.dashboard') }}"
                            class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg
                            {{ request()->routeIs('leader.dashboard')  ? 'active bg-gray-800 text-white border-start border-primary border-3'
                                    : 'hover:bg-gray-800 hover:text-white' }}">
                                {{--? 'active' : ''
                                 }}d-none  d-lg-inline d-md-inline
                                    --}}
                                <i class="fas fa-th-large fs-5"></i>
                                <span class="sidebar-text">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.team.index') }}"
                            class="sidebar-link nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg
                                {{ request()->routeIs('leader.team.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }}  ">
                                <i class="fas fa-users fs-5"></i>
                                <span class="sidebar-text ">Team</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.posts.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.posts.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-newspaper fs-5"></i>
                                <span class="sidebar-text">Posts</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.projects.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.projects.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-project-diagram fs-5"></i>
                                <span class="sidebar-text">Projects</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.tasks.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.tasks.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-clipboard-check fs-5"></i>
                                <span class="sidebar-text">Tasks</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link  d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 fw-medium {{ request()->routeIs('leader.calendar.*') ? 'active bg-primary rounded' : '' }}"
                            href="{{ route('leader.interface.calendar') }}">
                                <i class="fas fa-calendar-alt me-2 d-md-inline "></i>
                                <span class="sidebar-text">Calendar</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{-- route('leader.folders') --}}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->is('leader/folders*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-folder-closed fs-5"></i>
                                <span class="sidebar-text">Folders</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.notes') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.notes') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-note-sticky fs-5"></i>
                                <span class="sidebar-texte">Notes</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.notifications') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.notifications') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-bell fs-5"></i>
                                <span class="sidebar-text">Notifications</span>
                            </a>
                        </li>
                    </ul>

                </nav>

                <!--sidebar-footer User Profile Footer + Logout  p-4 border-top  border-gray-700 -->
                @auth
                    <div class="sidebar-footer flex-grow-1 py-4 px-3 overflow-y-auto  border-top  border-gray-700">
                        <div class="d-flex align-items-center justify-content-between">
                            {{--   <a href="{{ route('leader.profile') }}" class="d-flex align-items-center gap-3 text-decoration-none text-white flex-grow-1">
                                <img src="{{ auth()->user()->profile ?? asset('images/logo5.png') }}"   alt="Profile"  class="rounded-circle border border-gray-600"    style="width: 48px; height: 48px; object-fit: cover;">
                                <div class="d-none d-md-block">
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <small class="text-gray-400">{{ auth()->user()->email }}</small>
                                </div>
                            </a>   --}}
                            <a href="{{ route('leader.profile') }}" class="text-decoration-none flex-shrink-0">
                                <img src="{{ auth()->user()->profile ?? asset('images/logo5.png') }}"  alt="Profile"
                                    class="rounded-circle border border-2 border-gray-600"  style="width: 48px; height: 48px; object-fit: cover;">
                            </a>
                            <div class="flex-grow-1 user-info d-none d-lg-block">
                                <a href="{{ route('leader.profile') }}" class="text-decoration-none">
                                    <div class="fw-semibold text-white mb-0">{{ Str::limit(auth()->user()->name, 15) }}</div>
                                    <small class="text-gray-400">{{ Str::limit(auth()->user()->email, 20) }}</small>
                                </a>
                            </div>
                            <!-- Bouton Logout -->
                            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-3">
                                @csrf
                                <button type="submit"
                                        class="btn btn-outline-danger logout-btn btn-sm rounded-circle p-2"
                                        title="Logout"
                                        onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </aside>


<!-- Media query pour mobile : sidebar icônes seulement + contenu au-dessus -->
<style>

 /* Ajustement du contenu principal */
    main {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Responsive - Tablet et Mobile */
    @media (max-width: 950px) {
        .sidebar {
            width: var(--sidebar-collapsed-width) !important;
            min-width: var(--sidebar-collapsed-width) !important;
        }
        .sidebar .nav-text,
        .sidebar .brand-text,
        .sidebar .user-info,
        .sidebar .badge {
            display: none !important;
        } /**/

       *
        .sidebar .nav-link {
            justify-content: center;
            padding: 1rem !important;
        }




        .sidebar-footer .d-flex {
            justify-content: center;
        }
        /* Nav Links */
        .nav-link {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar .border-start {
            border-left: none !important;
        }

        .sidebar-header {
            justify-content: center;
        }


        .sidebar-footer .logout-btn {
            margin-left: 0 !important;
        }


         aside#sidebar {
            width: 100px !important;
            min-width: 80px !important;
        }

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

    @media (max-width: 558px) {
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
     @media (max-width: 768px) {
        .sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    /* Mobile - Sidebar en overlay
    @media (max-width: 767.98px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
            width: 280px !important;
        }

        .sidebar.show .nav-text,
        .sidebar.show .brand-text,
        .sidebar.show .user-info,
        .sidebar.show .badge {
            display: inline-block !important;
        }
    }*/

    .transition-all {
        transition: all 0.3s ease;
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

    /* Sidebar de base */
    .sidebar {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1040;
        /*background: linear-gradient(180deg, var(--bg-gray-950) 0%, var(--bg-dark) 100%);*/
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
        padding: 0.25rem 0.5rem;

    }

    .sidebar-link:hover {
        background-color: var(--bg-gray-800);
        color: white;
        transform: translateX(5px);
    }

    .sidebar-link.active {
        /*  */
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
