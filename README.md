
## About Site 

# TeamLink Full-Stack Laravel

## 🌐 Résumé du projet

**Team Link** est une application web full-stack construite avec Laravel, Laravel Breeze, Laravel Sanctum et Spatie Activity Log.  
Elle permet aux **leaders et membres d’une équipe** de collaborer efficacement, gérer des projets et des tâches, suivre l’avancement et conserver l’historique de toutes les actions importantes.

> "Empower your team, elevate your workflow. Team Link is the CRM for leaders and members to collaborate, analyze, and achieve more—together."

Le projet inclut à la fois :

- **Frontend Blade UI** via Laravel Breeze (login, register, dashboard)  
- **API sécurisée** via Laravel Sanctum  
- **Historique des actions** (modifications de tâches, deadlines, posts) via Spatie Activity Log  

---

## ⚙️ Installation étape par étape

###  Cloner le projet

git clone <url-du-projet>
cd teamlink-fullstack

### Installer les dépendances PHP
composer install

### installation  & Packages 

- composer create-project laravel/laravel Teaminks

## Autres packages utiles 
🟩 installer Laravel UI
👉 Génère des vues Blade + Bootstrap & Authentification simple et claire (login/register/logout)

- \Teaminks>composer require laravel/ui
- php artisan ui bootstrap --auth
- npm install 
- npm run dev
or - [npm install && npm run dev] 
-[ composer run dev]

    automatiquement :
        ⦁	/login
        ⦁	/register
        ⦁	/forgot-password
        ⦁	Layouts Bootstrap prêts
        ⦁	Vue Blade avec Bootstrap
        ⦁	Contrôleurs Auth


🟩 installer Laravel Sanctum :
👉  Pour gérer l'auth et les sessions proprement 

- composer require laravel/sanctum
- php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
- php artisan migrate

- php artisan install:api


🟩 installer Spatie Laravel Permission

👉 Obligatoire si tu veux gérer Leader & Member proprement.

composer require spatie/laravel-permission

    👉 Permet :
        * rôle Leader
        * rôle Member
        * middleware simple
        * contrôle des accès
        * Parfait pour ton sprint 2.


⭐ 🟩 installer Laravel Breeze (EN OPTION)
👉
- composer require laravel/breeze --dev
- php artisan breeze:install
    tu choisis la **stack Blade** si tu veux un frontend simple. Si tu veux du SPA + Vue/React, Breeze te propose Inertia.
- npm install && npm run dev
- php artisan migrate



⭐ Laravel Debugbar

👉 Pour débugger facilement :

- composer require barryvdh/laravel-debugbar --dev


⭐ 🟩 installer Spatie Activity Log

👉 Pour garder l’historique : tâches modifiées, deadlines demandées, etc.

- composer require spatie/laravel-activitylog
- php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
- php artisan migrate
    
    Cela crée la table activity_log dans ta base de données.
    Tu peux ensuite configurer le package via config/activitylog.php.


🟩 FullCalendar (frontend)

👉 Tu en as besoin pour les deadlines. &&  Tu peux l’utiliser en JS pur

⭐ composer require doctrine/dbal
- Using version ^4.4 for doctrine/dbal

---------------------------------

## Explication :

**Sanctum :** c’est un système d’authentification pour API / SPA / mobile, gérant soit des tokens, soit l’authentification par cookie (sessions + protection CSRF) selon le contexte. 
laravel.com
+1

**Breeze :** c’est un “starter-kit” / “scaffold” pour une application Laravel classique (backend + vues Blade, ou avec Inertia + Vue/React), qui fournit routes, contrôleurs, vues pour login / register / mot de passe oublié / reset / email verification etc. 
laravel.com
+1

**En gros :** Sanctum s’occupe de l’authentification au niveau API/token ou SPA, Breeze s’occupe de générer l’interface + les mécanismes “classiques” (login, register…) si tu fais un site ou une application web “monolithe”.

### Configurer Sanctum pour l’API

    - Vérifier config/sanctum.php

    - Ajouter middleware auth:sanctum aux routes API sécurisées

    - Tester avec Postman ou fetch/Axios depuis le frontend


## Tester l’intégration

    - Login/Register via Blade (Breeze) → doit créer token ou session

    - Consommer API sécurisée via JS ou Postman → authentification via Sanctum


--------------------------------------




### Navigation et fonctionnalités du site
#### Pages principales

Home – Accueil et présentation

Login / Register – Authentification des utilisateurs

Dashboard – Tableau de bord des tâches et projets

Team Link – Gestion des équipes et projets

#### Fonctionnalités pour le Leader

Créer des équipes et ajouter des membres

Assigner des projets et tâches

Suivre les deadlines et l’avancement

Consulter les analyses de performance

Notifications en temps réel

Créer et interagir avec des posts internes

#### Fonctionnalités pour le Membre

Rejoindre des équipes

Terminer les tâches assignées

Gagner des points et feedback

Consulter notes et calendrier personnel

Recevoir notifications instantanées

Interagir avec le leader

#### Collaboration

Communication d’équipe instantanée

Timeline des projets

Analyse des performances et suivi des tâches

### 📜 License

© 2025 Team Link. All rights reserved.


---

💡 


## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
