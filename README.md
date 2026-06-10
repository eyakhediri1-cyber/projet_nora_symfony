# NORA — Plateforme de Gestion d'Événements

Application web fullstack développée avec **Symfony 7.4**, couvrant l'ensemble du cycle de vie d'une plateforme événementielle : création, inscription, paiement, notification et administration.

---

## ✨ Fonctionnalités

| Module | Description |
|--------|-------------|
| **Événements** | CRUD complet avec upload d'image, tags, filtres avancés et pagination |
| **Inscriptions** | Workflow de statut (confirmée / en attente / annulée) + email de confirmation |
| **Authentification** | Inscription, login/logout, hiérarchie de rôles (User → Organisateur → Admin) |
| **API REST** | Exposition via API Platform avec groupes de sérialisation et Swagger UI |
| **Recherche** | QueryBuilder multi-critères : titre, catégorie, ville, tag |
| **Sessions** | Historique des 5 derniers événements consultés (FIFO, sans doublons) |
| **Console** | Commande `app:eventspot:report` — rapport complet avec stats et revenus |

---

## 🛠️ Stack Technique

`Symfony 7.4` · `Doctrine ORM` · `API Platform` · `Twig` · `Bootstrap 5` · `MySQL` · `PHPUnit` · `Mailtrap` · `KnpPaginatorBundle` · `SweetAlert2`

---

## 🏗️ Architecture

**5 entités** liées par des relations Doctrine :

```
User ──< Evenement >── TagEvenement
          │
          └──< Inscription >── User
          │
         Lieu
```

**Services injectés :** `EvenementManager` (places restantes, taux de remplissage), `FileUploader` (gestion images), `EventSpotExtension` (filtres Twig `time_ago`, `price_format`, `capacity_badge`)

---

## 🔐 Rôles & Accès

| Rôle | Permissions |
|------|-------------|
| Visiteur | Consulter les événements publiés |
| `ROLE_USER` | S'inscrire à un événement |
| `ROLE_ORGANISATEUR` | Créer et gérer ses propres événements |
| `ROLE_ADMIN` | Accès complet (lieux, tags, tous les événements) |

---

## 🚀 Installation

```bash
git clone <repo> && cd eventspot
composer install
cp .env .env.local  # configurer DATABASE_URL et MAILER_DSN

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony serve
```

**Comptes de test :**
| Email | Mot de passe | Rôle |
|-------|-------------|------|
| admin@eventspot.com | admin123 | Admin |
| orga1@eventspot.com | orga123 | Organisateur |
| user@eventspot.com | user123 | Utilisateur |

---

## 🧪 Tests

```bash
php bin/phpunit
```

Couvre : tests unitaires (`EvenementManager`), tests fonctionnels (contrôleurs, formulaires, flash messages) et tests API (codes HTTP, content-type, validation).

---

## 📡 API

Swagger UI disponible sur `/api` après installation.

```
GET    /api/evenements       → liste paginée
POST   /api/evenements       → créer (authentifié)
GET    /api/evenements/{id}  → détail
PUT    /api/evenements/{id}  → modifier
DELETE /api/evenements/{id}  → supprimer
```

---

*Projet académique — Module Symfony 7.4 · ISET Sousse DSI2*
