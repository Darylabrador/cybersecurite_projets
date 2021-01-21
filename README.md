# Projet de sécurité sur l'authentification / l'inscription

La conception et le développement de ce projet s'est effectué dans le cadre de la formation de Simplon pour l'aspect cybersécurité.

Ce projet utilise les technologies suivantes :

- backend : Laravel 8 (API)
- frontend : Blade / JS


Identifiant du compte admin : 

- identifiant : admin@gmail.com
- mot de passe : password

## Initialisation du projet

Après avoir fait un git clone de ce projet, vous devez effectué les actions suivantes : 

- composer install
- php artisan migrate:fresh --seed
- php artisan passport:install --force
- php artisan queue:work

Ensuite, vous devez créer et modifier le fichier .env pour les lignes suivantes : 

- DB_DATABASE=
- DB_USERNAME=
- DB_PASSWORD=
- QUEUE_CONNECTION=database

Les informations concernant le passport se trouve dans votre base de données.

> Les ressources annexes de configuration peuvent être retrouver dans le dossier documentation