{{-- resources/views/layouts/partials/sidebar.blade.php --}}

<!-- Sidebar -->
<aside class="sidebar bg-gray-950 border-end border-gray-800 vh-100 position-fixed d-flex flex-column transition-all"
    style="width: 280px; z-index: 1000;" id="sidebar">
    <div class="d-flex flex-column h-100">

        {{-- Logo & Brand --}}
        <div class="sidebar-header p-4 border-bottom border-gray-800">
            <a href="{{ route('leader.dashboard') }}"  class="d-flex align-items-center gap-3 text-white text-decoration-none">
               <img src="{{ asset('images/logo5.png') }}"
                     alt="Teamink Logo"
                     class="logo-img rounded-circle border border-2 border-primary"
                     style="height: 45px; width: 45px; object-fit: cover;">
                <div class="brand-text d-none d-lg-block">
                    <h2 class="fs-4 fw-bold mb-0 text-primary">Teaminks</h2>
                    <small class="text-gray-400"> Workspace</small>
                </div>
            </a>
        </div>

        {{-- Navigation Menu --}}
        <nav class="sidebar-nav flex-grow-1 py-4 px-3 overflow-y-auto">
            <ul class="nav flex-column gap-2">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('leader.dashboard') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.dashboard') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-th-large fs-5"></i>
                        <span class="nav-text">Dashboard</span>
                        @if(request()->routeIs('leader.dashboard'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Team --}}
                <li class="nav-item">
                    <a href="{{ route('leader.team.index') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.team.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-users fs-5"></i>
                        <span class="nav-text">Team</span>
                        @if(request()->routeIs('leader.team.*'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Posts --}}
                <li class="nav-item">
                    <a href="{{ route('leader.posts.index') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.posts.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-newspaper fs-5"></i>
                        <span class="nav-text">Posts</span>
                        @if(request()->routeIs('leader.posts.*'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Projects --}}
                <li class="nav-item">
                    <a href="{{ route('leader.projects.index') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.projects.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-project-diagram fs-5"></i>
                        <span class="nav-text">Projects</span>
                        @if(request()->routeIs('leader.projects.*'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Tasks --}}
                <li class="nav-item">
                    <a href="{{ route('leader.tasks.index') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.tasks.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-clipboard-check fs-5"></i>
                        <span class="nav-text">Tasks</span>
                        @if(request()->routeIs('leader.tasks.*'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Calendar --}}
                <li class="nav-item">
                    <a href="{{ route('leader.interface.calendar') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.calendar.*') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-calendar-alt fs-5"></i>
                        <span class="nav-text">Calendar</span>
                        @if(request()->routeIs('leader.calendar.*'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Divider --}}
                <li class="nav-divider my-3">
                    <hr class="border-gray-800">
                </li>

                {{-- Folders --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 hover-link">
                        <i class="fas fa-folder fs-5"></i>
                        <span class="nav-text">Folders</span>
                        <span class="badge bg-warning text-dark ms-auto">Soon</span>
                    </a>
                </li>

                {{-- Notes --}}
                <li class="nav-item">
                    <a href="{{ route('leader.notes') }}"
                       class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400
                              {{ request()->routeIs('leader.notes') ? 'active bg-gray-800 text-white border-start border-primary border-3' : 'hover-link' }}">
                        <i class="fas fa-note-sticky fs-5"></i>
                        <span class="nav-text">Notes</span>
                        @if(request()->routeIs('leader.notes'))
                            <i class="fas fa-chevron-right ms-auto text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Notifications --}}
                <li class="nav-item">
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
            </ul>
        </nav>

        {{-- User Profile Footer --}}
        <div class="sidebar-footer p-4 border-top border-gray-800">
            @auth
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('leader.profile') }}" class="text-decoration-none flex-shrink-0">
                        <img src="{{ auth()->user()->profile ?? asset('images/logo5.png') }}"
                             alt="Profile"
                             class="rounded-circle border border-2 border-gray-600"
                             style="width: 48px; height: 48px; object-fit: cover;">
                    </a>

                    <div class="flex-grow-1 user-info d-none d-lg-block">
                        <a href="{{ route('leader.profile') }}" class="text-decoration-none">
                            <div class="fw-semibold text-white mb-0">{{ Str::limit(auth()->user()->name, 15) }}</div>
                            <small class="text-gray-400">{{ Str::limit(auth()->user()->email, 20) }}</small>
                        </a>
                    </div>

                    {{-- Logout Button --}}
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-outline-danger btn-sm rounded-circle p-2 logout-btn"
                                title="Logout"
                                onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</aside>

{{-- Overlay pour mobile --}}
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

{{-- Styles --}}
<style>
    /* Variables
    */
    :root {
        --sidebar-width: 280px;
        --sidebar-collapsed-width: 80px;
        --primary-color: #3b82f6;
        --gray-950: #030712;
        --gray-900: #111827;
        --gray-800: #1f2937;
        --gray-700: #374151;
        --gray-600: #4b5563;
        --gray-400: #9ca3af;

    }

    /* Sidebar Base */
    .sidebar {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(180deg,  0%, 100%);
    }

    /* Logo Animation */
    .logo-img {
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        transform: rotate(360deg) scale(1.1);
    }

    /* Nav Links */
    .nav-link {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .hover-link:hover {
        color: white !important;
        transform: translateX(5px);
         background-color: #1e293b !important;
            border-left-color: #3b82f6 !important;
    }

    .nav-link.active {
        background-color: var(--gray-800);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Hover Effect */
    .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--primary-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .hover-link:hover::before {
        transform: scaleY(1);
    }

    /* Scrollbar Styling */
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: var(--gray-900);
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: var(--gray-700);
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: var(--gray-600);
    }

    /* Logout Button Animation */
    .logout-btn {
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        transform: rotate(360deg) scale(1.1);
        background-color: #dc3545 !important;
        color: white !important;
        border-color: #dc3545 !important;
    }

    /* Sidebar Overlay  for mobile*/
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(35, 1, 1, 0.5);
        z-index: 999;
        display: none;
        backdrop-filter: blur(4px);
    }

    .sidebar-overlay.show {
        display: block;
    }

    /* Responsive - Tablet et Mobile */
    @media (max-width: 991.98px) {
       /* .sidebar {
            width: var(--sidebar-collapsed-width) !important;
            min-width: var(--sidebar-collapsed-width) !important;
        }


        .sidebar .nav-text,
        .sidebar .brand-text,
        .sidebar .user-info,
        .sidebar .badge {
            display: none !important;
        } */

        .sidebar .nav-link {
            justify-content: center;
            padding: 1rem !important;
        }

        .sidebar .border-start {
            border-left: none !important;
        }

        .sidebar-header {
            justify-content: center;
        }

        .sidebar-footer .d-flex {
            justify-content: center;
        }

        .sidebar-footer .logout-btn {
            margin-left: 0 !important;
        }
        /*  */

         aside#sidebar {
            width: 80px !important;
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

    /* Mobile - Sidebar en overlay */
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
    }

    /* Dark Mode Enhancement   */
    .bg-gray-950 { background-color: var(--gray-950); }
    .bg-gray-900 { background-color: var(--gray-900); }
    .bg-gray-800 { background-color: var(--gray-800); }
    .border-gray-800 { border-color: var(--gray-800) !important; }
    .text-gray-400 { color: var(--gray-400); }

</style>

{{-- JavaScript pour mobile --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Toggle sidebar sur mobile
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    // Fermer la sidebar en cliquant sur l'overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Fermer la sidebar après clic sur un lien (mobile)
    if (window.innerWidth <= 768) {
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        });
    }
});
</script>
