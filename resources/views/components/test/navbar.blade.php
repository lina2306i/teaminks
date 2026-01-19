<nav class="navbar navbar-expand-lg bg-gray-900 border-bottom border-gray-500 fixed-top">
    <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex justify-content-between align-items-center w-100">

            <!-- Logo + Titre -->
            <a class="navbar-brand d-flex align-items-center text-white" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-v0.7.1.jpg') }}" alt="Teaminks Logo" class="me-2" style="height: 40px;">
                <span class="d-none d-md-block fs-4 fw-bold">Teaminks</span>
            </a>

            <!-- Liens de navigation -->
            <div class="d-flex gap-3">
                <a href="{{ route('home') }}"
                   class="text-white px-3 py-2 rounded {{ request()->routeIs('home') ? 'bg-gray-700' : 'hover:bg-gray-600' }}
                          transition duration-200">
                    Home
                </a>

                <a href="{{ route('login.form') }}"
                   class="text-white px-3 py-2 rounded {{ request()->routeIs('login') ? 'bg-gray-700' : 'hover:bg-gray-600' }}
                          transition duration-200">
                    Login
                </a>

                <a href="{{ route('register.form') }}"
                   class="text-white px-3 py-2 rounded {{ request()->routeIs('register') ? 'bg-gray-700' : 'hover:bg-gray-600' }}
                          transition duration-200">
                    Register
                </a>
            </div>

        </div>
    </div>
</nav>

<!-- Style supplémentaire pour le hover (Bootstrap ne gère pas hover:bg-* nativement) -->
<style>
    .hover\:bg-gray-600:hover {
        background-color: #374151 !important; /* gray-600 */
    }
    .bg-gray-700 {
        background-color: #334155 !important;
    }
    .bg-gray-900 {
        background-color: #111827 !important;
    }
</style>
