# EventSpot — Plateforme de Gestion d'Événements

Mini-Projet Symfony 7.4 — DSI 2.3 2026

## Installation

```bash
git clone https://github.com/ISET-DSI2-3-2026/miniprojet2026-les-2-mousquetaires.git
cd miniprojet2026-les-2-mousquetaires
composer install
```

Copie `.env` et configure ta base de données :

```bash
DATABASE_URL="mysql://user:password@127.0.0.1:3306/eventspot"
```

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n
php bin/console doctrine:fixtures:load -n
symfony server:start
```

## Config Mailtrap

Dans `.env` :
## Identifiants de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@eventspot.com | admin123 |
| Organisateur 1 | orga1@eventspot.com | orga123 |
| Organisateur 2 | orga2@eventspot.com | orga123 |
| Participant | user@example.com | user123 |

## Schéma des relations

- **User** → **Evenement** : ManyToOne (organisateur)
- **User** → **Inscription** : ManyToOne (participant)
- **Lieu** → **Evenement** : OneToMany
- **Evenement** → **Inscription** : OneToMany
- **Evenement** ↔ **TagEvenement** : ManyToMany

## Lancer les tests

```bash
php bin/phpunit
```