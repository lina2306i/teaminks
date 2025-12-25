
<!-- Sidebar -->
        <aside class="bg-gray-950 border-end border-gray-800 vh-100 position-fixed d-flex flex-column transition-all"
            style="width: 280px; z-index: 1000;" id="sidebar">
            <div class="d-flex flex-column h-100 justify-content-between">

                <!-- Logo + App Name -?email <div class="p-4 border-bottom border-gray-700 text-center">
                    <a href="{ { route('leader.dashboard') }}" class="d-flex align-items-center gap-3 text-white text-decoration-none justify-content-center">
                        <img src="{ { asset('images/logo5.png') }}" alt="Teamink Logo" class="img-fluid" style="height: 40px; border-radius: 50px;border: 2px solid darkblue;">
                        <h2 class="fs-5 fw-bold mb-0 d-none d-md-block">Teamink</h2>
                    </a>
                </div> -->


                <!-- Navigation Menu -->
                <nav class="flex-grow-1 py-4 px-3 overflow-y-auto">
                    <ul class="nav flex-column gap-2">

                        <li class="nav-item">
                            <a href="{{ route('leader.dashboard') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.dashboard') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-grid-horizontal fs-5"></i>
                                <span class="d-none d-lg-inline d-md-inline">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.team.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.team.*') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-users fs-5"></i>
                                <span class="d-none d-lg-inline d-md-inline">Team</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.posts.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.posts.*') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-newspaper fs-5"></i>
                                <span class="d-none d-lg-inline d-md-inline">Posts</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.projects.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.projects.*') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-project-diagram fs-5"></i>
                                <span class="d-none  d-lg-inline d-md-inline">Projects</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.tasks.index') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.tasks.*') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-clipboard-check fs-5"></i>
                                <span class="d-none d-lg-inline d-md-inline">Tasks</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{-- route('leader.folders') --}}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->is('leader/folders*') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-folder-closed fs-5"></i>
                                <span class="d-none d-lg-inline d-md-inline">Folders</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.notes') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.notes') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-note-sticky fs-5"></i>
                                <span class="d-none d-lg-inline d-md-inline">Notes</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('leader.notifications') }}"
                            class="nav-link d-flex align-items-center gap-3 py-3 px-4 rounded-lg text-gray-400 {{ request()->routeIs('leader.notifications') ? 'bg-gray-800 text-white border-start border-primary border-3' : 'hover:bg-gray-800 hover:text-white' }} transition-all">
                                <i class="fas fa-bell fs-5"></i>
                                <span class="d-none  d-lg-inline d-md-inline">Notifications</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Profile Footer + Logout  p-4 border-top  border-gray-700 -->
                <div class="flex-grow-1 py-4 px-3 overflow-y-auto  border-top  border-gray-700">
                    @auth
                        <div class="d-flex align-items-center justify-content-between"></div>
                            <a href="{{ route('leader.profile') }}" class="d-flex align-items-center gap-3 text-decoration-none text-white flex-grow-1">
                                <img src="{{ auth()->user()->profile ?? asset('images/logo5.png') }}"
                                    alt="Profile"
                                    class="rounded-circle border border-gray-600"
                                    style="width: 48px; height: 48px; object-fit: cover;">
                                <div class="d-none d-md-block">
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <small class="text-gray-400">{{ auth()->user()->email }}</small>
                                </div>
                            </a>
                            <!-- Bouton Logout -->
                            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-3">
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


<!-- Media query pour mobile : sidebar icônes seulement + contenu au-dessus -->
<style>
    @media (max-width: 992px) {
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

    .transition-all {
        transition: all 0.3s ease;
    }
</style>
