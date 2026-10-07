# BiblioTech

Application Laravel de gestion d'une bibliothèque : livres, adhérents et emprunts.

## stack
 - Laravel 13
 - Tailwind,vite et Fontawesome

## Installation

```bash
composer install
npm install
npm run build
php artisan migrate --seed
composer run dev
```

`php artisan migrate --seed` est indispensable : les migrations créent les tables et le seed charge le compte de démonstration ainsi que les données initiales. Pour réinitialiser la base : `php artisan migrate:fresh --seed`.

## Compte et données de démonstration

- Connexion : **admin@admin.com** / **password**
- Le seeder crée 20 livres et 12 adhérents. Il ne crée aucun emprunt.
- Les inscriptions de nouveaux comptes sont également disponibles.

## Fonctionnalités

- Gestion des livres et des adhérents (création, modification, suppression) ; ajout de photos aux profils.
- Recherche et filtres sur les livres ; recherche d'adhérents lors de la création d'un emprunt.
- Emprunts et retours avec mise à jour du stock, durée par défaut de 14 jours (modifiable de 1 à 60 jours).
- Un seul emprunt en cours par adhérent ; un retard non rendu bloque les nouveaux emprunts.
- Tableau de bord, suivi des retards, historique des emprunts et export CSV filtrable.
- Listes paginées pour les livres (8 par page) et les adhérents (10 par page).

Les emprunts ne sont pas paginés. Le suivi des pénalités et les réservations ne sont pas disponibles.
