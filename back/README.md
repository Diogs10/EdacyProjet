
---

## ⚙️ Prérequis

### 🔗 Outils nécessaires :

- PHP ≥ 8.1 (⚠️ PHP 8.4 non compatible avec certains packages, préférer PHP 8.1 ou 8.2)
- Composer
- MySQL
- Node.js ≥ 16
- Angular CLI (`npm install -g @angular/cli`)
- Un serveur web local (Laravel Valet, XAMPP, etc.)

---

## 🚀 Installation

### 1. Backend Laravel (`/backend`)

```bash

# Installer les dépendances PHP
composer install

Or

composer install --ignore-platform-req=php --ignore-platform-req=ext-gd

# Copier le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer la base de données (ex: sonatel_academy) via MySQL ou phpMyAdmin

# Configurer le .env avec les identifiants MySQL
# DB_DATABASE=sonatel_academy
# DB_USERNAME=root
# DB_PASSWORD=

# Lancer les migrations et les seeders
php artisan migrate --seed

# Lancer le serveur de développement
php artisan serve

🔐 Authentification : Sanctum est utilisé pour gérer l'authentification par tokens (API REST).

📦 Import Excel : Le package maatwebsite/excel permet d'importer les listes d’étudiants.

⚠️ Si vous rencontrez une erreur ext-gd missing, activez l’extension GD dans php.ini.
