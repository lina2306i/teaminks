<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token (si Laravel) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Teaminks - Gestion d'équipe puissante</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles personnalisés -->
    <link rel="stylesheet" href="{{ asset('css/styleSign.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    @include('components.navbar2')

    <main class="py-4">
        <div class="container" id="container">
            <!-- Inscription -->
            <div class="form-container sing-up">
                <form>
                    <h1>Créez votre compte</h1>

                    <div class="social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>

                    <span>ou utilisez votre email pour vous inscrire</span>

                    <input type="text" placeholder="Nom">
                    <input type="email" placeholder="Email">
                    <input type="password" placeholder="Mot de passe">

                    <button>S'inscrire</button>
                </form>
            </div>

            <!-- Connexion -->
            <div class="form-container sing-in">
                <form>
                    <h1>Connectez-vous</h1>

                    <div class="social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>

                    <span>ou utilisez votre email et mot de passe</span>

                    <input type="email" placeholder="Email">
                    <input type="password" placeholder="Mot de passe">
                    <a href="#">Mot de passe oublié ?</a>
                    <button>Se connecter</button>
                </form>
            </div>

            <div class="toggle-container">
                <div class="toggle">
                    <div class="toggle-panel toggle-left">
                        <h1>Bienvenue à nouveau !</h1>
                        <p>Entrez vos identifiants pour accéder à tous les outils de gestion d'équipe.</p>
                        <button class="hidden" id="login">Se connecter</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>Hello !</h1>
                        <p>Inscrivez-vous pour découvrir une gestion d'équipe puissante et intuitive.</p>
                        <button class="hidden" id="register">S'inscrire</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 bg-dark text-white">
        <div class="flex">
            <div class="mb-3">
                <a href="https://github.com/lina2306i" target="_blank" class="text-white mx-3 fs-3">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/linalabiadh" target="_blank" class="text-white mx-3 fs-3">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
            <p class="text-gray-400 mb-0">&copy; 2026 - Teaminks. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Script personnalisé -->
    <script src="{{ asset('js/scriptSign.js') }}"></script>

    <!-- Script de toggle -->
    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>

    <!-- Bootstrap JS (optionnel mais recommandé pour certains composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
