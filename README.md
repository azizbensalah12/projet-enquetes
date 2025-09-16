# Projet Technologies Web 2A — Gestion d’enquêtes (MVC + PDO)

## Prérequis
- PHP 8.1+ avec extensions `pdo`, `pdo_mysql`, `mbstring`
- Composer
- MySQL/MariaDB (ou compatible)
- Git

## Installation
```bash
git clone <votre-repo> web-survey-mvc
cd web-survey-mvc
composer install
cp .env.example .env
# Éditer .env pour vos accès MySQL
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS web DEFAULT CHARACTER SET utf8mb4"
mysql -u root -p web < sql/schema.sql
php -S localhost:8000 -t public
```

Identifiants admin par défaut : `admin@example.com` / `admin123`.

## Structure
- `public/` Front controller (`index.php`), assets, `.htaccess` (URLs propres)
- `app/Core/` Router, DB (PDO), Session/Auth, base Controller/Model
- `app/Models/` Modèles (User, Campaign, Question, Response)
- `app/Controllers/` CRUD et logique métier
- `app/Views/` Vues + `layouts` (Front/Back)
- `sql/schema.sql` Schéma + seed
- `.env` Configuration (chargée via `vlucas/phpdotenv`)

## Rôles & Scénario
- **Administrateur** : gère utilisateurs, crée des campagnes ciblées
- **Agent** : gère campagnes et questions, assigne des campagnes à des clients spécifiques
- **Client** : consulte et répond aux enquêtes publiées qui lui sont assignées

## Validation & Contrôles de saisie
- **Front** : attributs HTML5 + JS léger (`public/assets/js/validate.js`)
- **Back** : validations PHP (trim, required, types) dans les contrôleurs avant insert/update

## Historique Git
- Créez une branche par feature : `feat/auth`, `feat/campaigns`, etc.
- Commits fréquents et descriptifs (en anglais/français).
- Utilisez des tags `v0.1`, `v0.2`… jusqu’à la soutenance.

## Sécurité
- Hash des mots de passe (`password_hash` BCRYPT)
- Sessions côté serveur, garde par rôle dans les contrôleurs
- Requêtes préparées PDO partout

## Tests (suggestion)
- Placez vos tests unitaires (PHPUnit) dans `tests/`, configurez `phpunit.xml.dist`.
```