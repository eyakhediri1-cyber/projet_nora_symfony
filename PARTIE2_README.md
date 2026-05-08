# 🎉 Partie 2 - Formulaires, Validation et Templates

## ✅ Statut : COMPLÉTÉE

Toute la Partie 2 du TP2 a été implémentée avec succès ! Voici ce qui a été créé :

---

## 📁 Structure créée

### **FormTypes** (`src/Form/`)
- ✅ `EvenementType.php` - Formulaire d'événement (EntityType lieu, ChoiceType catégorie/statut, DateTimeType, MoneyType, EntityType tags, FileType image)
- ✅ `InscriptionType.php` - Formulaire d'inscription (TextareaType commentaire)
- ✅ `LieuType.php` - Formulaire de lieu
- ✅ `TagEvenementType.php` - Formulaire de tag

### **Contrôleurs** (`src/Controller/`)
- ✅ `EvenementController.php` - Routes complètes : accueil, liste, détail, créer, modifier, supprimer, s'inscrire
- ✅ `LieuController.php` - Liste et création de lieux
- ✅ `TagEvenementController.php` - Liste, création et suppression de tags

### **Templates Twig** (`templates/`)
- ✅ `base.html.twig` - Layout Bootstrap 5 avec navbar, footer et messages flash
- ✅ `evenement/accueil.html.twig` - Page d'accueil avec 6 prochains événements
- ✅ `evenement/index.html.twig` - Liste complète avec tableau responsive
- ✅ `evenement/detail.html.twig` - Détail avec jauge de remplissage et tags colorés
- ✅ `evenement/nouveau.html.twig` - Formulaire de création
- ✅ `evenement/modifier.html.twig` - Formulaire de modification
- ✅ `evenement/inscription.html.twig` - Formulaire d'inscription
- ✅ `lieu/index.html.twig` - Liste des lieux
- ✅ `lieu/nouveau.html.twig` - Création de lieu
- ✅ `tag_evenement/index.html.twig` - Liste des tags avec couleurs
- ✅ `tag_evenement/nouveau.html.twig` - Création de tag

### **Repository Update**
- ✅ `EvenementRepository.php` - Méthode `findUpcoming()` pour les 6 prochains événements publiés

---

## 🎨 Fonctionnalités

### ✨ Bootstrap 5 intégré
- Gradient background (violet/rose)
- Navbar responsive avec navigation
- Carte (cards) avec hover effects
- Système d'alertes pour messages flash
- Layout responsive mobile-first

### 🎯 Formulaires Smart
- Validation côté serveur
- Messages d'erreur affichés sous chaque champ
- Protection CSRF sur les suppressions
- Confirmations avant suppression
- Emojis dans les labels pour meilleure UX

### 📊 Liste des événements
- Tableau responsive avec badges colorés :
  - 🎤 Conférence (bleu)
  - 🔧 Atelier (jaune)
  - 👥 Meetup (bleu)
  - 📚 Formation (vert)
  - 🎵 Concert (rouge)
- Statuts : 📝 Brouillon, 🟢 Publié, 🔴 Complet, ⚫ Annulé
- Barre de progression pour les places

### 🎪 Détail événement
- Image grande (ou placeholder emoji)
- Jauge de remplissage dynamique
- Tags associés avec couleurs personnalisées
- Boutons d'inscription intelligents (désactivés si complet/annulé)
- Sidebar avec statistiques
- Boutons de modification et suppression

### 💬 Messages Flash
- ✅ Succès (vert)
- ⚠️ Avertissement (orange)
- ❌ Erreur (rouge)
- Fermeture automatique possible
- Position en haut de la page

---

## 🚀 Comment tester

### 1. Lancer le serveur Symfony
```bash
cd /home/eya/Eya2eme/Symfony/miniprojet2026-les-2-mousquetaires
symfony server:start
```

### 2. Accéder à l'application
Ouvrez votre navigateur à : **http://localhost:8000**

### 3. Test des fonctionnalités

#### Créer un lieu
1. Allez sur **📍 Lieux**
2. Cliquez sur **➕ Ajouter un lieu**
3. Remplissez le formulaire
4. Cliquez sur **💾 Enregistrer**
5. ✅ Message de confirmation s'affiche

#### Créer un tag
1. Allez sur **🏷️ Tags**
2. Cliquez sur **➕ Ajouter un tag**
3. Entrez un nom et choisissez une couleur
4. Cliquez sur **💾 Enregistrer**
5. ✅ Message de confirmation s'affiche

#### Créer un événement
1. Allez sur **📅 Événements**
2. Cliquez sur **➕ Créer un événement**
3. Remplissez TOUS les champs :
   - Titre (min 5 caractères)
   - Description (min 30 caractères)
   - Date de début et fin
   - Lieu (select)
   - Capacité maximale (min 1)
   - Catégorie (select)
   - Statut (select)
   - Optionnel : Prix, Image, Tags
4. Cliquez sur **💾 Enregistrer**
5. ✅ Redirigé vers la liste avec message de succès

#### Voir un événement
1. Depuis la liste, cliquez sur **👁️ Voir les détails**
2. Affichage du détail complet :
   - Grande image
   - Jauge de remplissage
   - Tous les détails (date, lieu, prix, etc.)
   - Tags avec couleurs

#### S'inscrire à un événement
1. Sur la page de détail, cliquez sur **👉 S'inscrire maintenant**
2. Optionnel : Ajoutez un commentaire
3. Cliquez sur **✅ Confirmer l'inscription**
4. ✅ Message de confirmation
5. La jauge is updated

#### Modifier un événement
1. Sur la page de détail, cliquez sur **✏️ Modifier**
2. Modifiez les champs souhaités
3. Cliquez sur **💾 Enregistrer**
4. ✅ Redirigé vers le détail avec message

#### Supprimer un événement
1. Sur la liste ou le détail, cliquez sur **🗑️ Supprimer**
2. Confirmez la suppression
3. ✅ Redirigé vers la liste avec message
4. L'événement est supprimé

---

## 📋 Validations implémentées

### Événement
- ✅ Titre : NotBlank, Length(min: 5, max: 255)
- ✅ Description : NotBlank, Length(min: 30)
- ✅ Date début/fin : NotNull, DateTime valide
- ✅ Lieu : NotBlank (ManyToOne)
- ✅ Capacité : Range(min: 1)
- ✅ Prix : nullable, PositiveOrZero
- ✅ Catégorie : Choice parmi 5 options
- ✅ Statut : Choice parmi 4 options
- ✅ Image : File (JPEG/PNG/WebP, max 2Mo, mapped: false)
- ✅ Tags : ManyToMany (expanded: true, by_reference: false)

### Inscription
- ✅ Commentaire : nullable, Length(max: 500)

### Lieu
- ✅ Nom : NotBlank, Unique
- ✅ Adresse : NotBlank
- ✅ Ville : NotBlank
- ✅ Capacité : Range(min: 1)

### Tag
- ✅ Nom : NotBlank, Unique
- ✅ Couleur : Regex #[0-9A-Fa-f]{6}

---

## 🔐 Sécurité

### ✅ Protection CSRF
- Token CSRF sur formulaires
- Token CSRF sur bouton de suppression (POST uniquement)
- Confirmations JavaScript avant suppression

### ✅ Méthodes HTTP appropriées
- GET pour affichage
- POST pour création/modification/suppression
- Validation côté serveur

---

## 🎨 Utiliser un template personnalisé

Le projet utilise actuellement **Bootstrap 5** intégré depuis CDN. Pour utiliser un template personnalisé :

### Option 1 : ThemeWagon (Template gratuit)
1. Allez sur https://themewagon.com/
2. Choisissez un template Bootstrap 5 (ex: "EventHub", "EventWave", etc.)
3. Téléchargez le ZIP
4. Extrayez-le
5. Copiez les fichiers `assets/css/style.css` dans `public/css/custom-theme.css`
6. Copiez les images dans `public/img/theme/`
7. Mettez à jour `templates/base.html.twig` :

```twig
<!-- À la place de Bootstrap CDN -->
<link rel="stylesheet" href="{{ asset('css/custom-theme.css') }}">
```

### Option 2 : Template personnalisé maison
1. Créez `public/css/theme.css`
2. Créez `public/js/theme.js` (optionnel)
3. Modifiez la navbar et le footer dans `templates/base.html.twig`
4. Ajoutez votre branding/couleurs

---

## 📚 Ressources

| Élément | Documentation |
|---------|---|
| FormType | https://symfony.com/doc/current/forms.html |
| Validation | https://symfony.com/doc/current/validation.html |
| Relations Doctrine | https://symfony.com/doc/current/doctrine/associations.html |
| Twig | https://twig.symfony.com/doc/ |
| Bootstrap 5 | https://getbootstrap.com/docs/5.3/ |

---

## 🔄 Workflow Git

```bash
# Stager tous les fichiers
git add .

# Commit avec message conventionnel
git commit -m "feat: Formulaires, validations et templates Bootstrap 5 - Partie 2 complète"

# Pousser les changements
git push origin main
```

---

**✨ Bravo ! Partie 2 du TP2 complètement fonctionnelle ! 🎉**
