# 🎨 Guide : Intégration d'un Template ThemeWagon

## Étape 1 : Choisir un template sur ThemeWagon

1. Allez sur https://themewagon.com/
2. Filtrez par **"Bootstrap 5"** ou **"Events"**
3. Choisissez un template gratuit qui vous plaît (ex: "EventHub", "EventWave", "Eventus", etc.)
4. Téléchargez le ZIP

**Exemples de templates recommandés** :
- EventHub (parfait pour les événements)
- EventWave (moderne et minimaliste)
- Conference Hub (professionnel)

---

## Étape 2 : Extraire les fichiers

```bash
# Extractez le ZIP
unzip EventHub_bootstrap_template.zip

# Naviguez dans le dossier
cd EventHub/
```

---

## Étape 3 : Copier les assets dans votre projet Symfony

```bash
# Depuis le répertoire du template
cp -r assets/css/* /path/to/miniprojet/public/css/
cp -r assets/js/* /path/to/miniprojet/public/js/
cp -r assets/images/* /path/to/miniprojet/public/img/
cp -r assets/fonts/* /path/to/miniprojet/public/fonts/
```

---

## Étape 4 : Adapter le template HTML

### Option A : Remplacer complètement le layout (recommandé)

1. Ouvrez le fichier `index.html` du template
2. Copiez-le dans `templates/base-themewagon.html.twig`
3. Adaptez-le en remplaçant les sections par les blocs Twig :

```twig
<!-- À la place de <head> -->
{% block stylesheets %}
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-overrides.css') }}">
{% endblock %}

<!-- À la place de <nav> -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ path('app_accueil') }}">🎉 Événements</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ path('app_accueil') }}">Accueil</a>
                <a class="nav-link" href="{{ path('app_evenements') }}">Événements</a>
                <a class="nav-link" href="{{ path('app_lieux') }}">Lieux</a>
                <a class="nav-link" href="{{ path('app_tags') }}">Tags</a>
            </div>
        </div>
    </div>
</nav>

<!-- À la place du contenu -->
<main class="container my-5">
    {% block body %}{% endblock %}
</main>

<!-- À la place de <footer> -->
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <p>&copy; {{ "now"|date("Y") }} — Les 2 Mousquetaires</p>
    </div>
</footer>

<!-- À la place de <script> -->
{% block javascripts %}
    <script src="{{ asset('js/theme.js') }}"></script>
{% endblock %}
```

### Option B : Fusionner progressivement

Si vous préférez garder votre layout actuel et ajouter juste les styles :

```twig
<!-- Dans base.html.twig, mettez à jour le <head> -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{% block title %}Événements{% endblock %}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS du template ThemeWagon -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-responsive.css') }}">
    
    <!-- Vos CSS personnalisés (par-dessus) -->
    <link rel="stylesheet" href="{{ asset('css/custom-overrides.css') }}">
    
    {% block stylesheets %}{% endblock %}
</head>
```

---

## Étape 5 : Mettre à jour les imports CSS/JS

Dans `templates/base.html.twig`, assurez-vous que les imports correspondent :

```twig
<!-- Vérifiez que les fichiers existent dans public/css/ et public/js/ -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-icons.min.css') }}">
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
```

---

## Étape 6 : Adapter les classes CSS

Le template ThemeWagon peut avoir ses propres classes. Mettez à jour vos templates Twig si nécessaire :

**Avant** (Bootstrap 5 vanilla) :
```twig
<div class="btn btn-primary">Bouton</div>
```

**Après** (avec ThemeWagon) :
```twig
<div class="btn btn-primary theme-button">Bouton</div>
```

Vérifiez les **cartes, boutons, badges, formulaires** du template et adaptez.

---

## Étape 7 : Vérifier la compatibilité

### Tests obligatoires

```bash
# Lancer le serveur
symfony server:start

# Checker
- Page d'accueil : responsive et bien stylisée ✅
- Navbar : responsive et navigation OK ✅
- Formulaires : champs bien stylisés ✅
- Messages flash : affichage correct ✅
- Badges : couleurs correctes ✅
- Boutons : hovers effets ✅
- Responsive mobile : OK ✅
```

---

## Étape 8 : Git - Saauvegarder les changements

```bash
cd /home/eya/Eya2eme/Symfony/miniprojet2026-les-2-mousquetaires

git add .
git commit -m "feat: Intégration du template ThemeWagon pour meilleure UX"
git push origin main
```

---

## 🔗 Ressources ThemeWagon à copier

| Fichier | Destination |
|---------|-------------|
| `css/style.css` | `public/css/` |
| `css/bootstrap-icons.min.css` | `public/css/` |
| `js/main.js` | `public/js/` |
| `images/` | `public/img/` |
| `fonts/` | `public/fonts/` |

---

## ⚠️ Pièges à éviter

❌ **NE PAS** :
- Copier les fichiers HTML du template directement (les compatibiliser d'abord avec Twig)
- Oublier d'importer le CSS du template
- Lier en dur les chemins d'images (utilisez `{{ asset() }}`)
- Ignorer les dépendances JavaScript du template

✅ **À FAIRE** :
- Tester la responsivité mobile
- Vérifier que Bootstrap 5 ne conflicte pas avec le template
- Minifier les CSS/JS en production
- Conserver une copie du template original en cas de besoin

---

## 📝 Exemple complet : Intégrer EventHub

### 1. Télécharger EventHub depuis ThemeWagon

```bash
wget https://demo.themewagon.com/files/eventhub.zip
unzip eventhub.zip
cd EventHub/
```

### 2. Copier les fichiers

```bash
cp -r html/assets/css/* ~/Eya2eme/Symfony/miniprojet2026-les-2-mousquetaires/public/css/
cp -r html/assets/js/* ~/Eya2eme/Symfony/miniprojet2026-les-2-mousquetaires/public/js/
cp -r html/assets/images/* ~/Eya2eme/Symfony/miniprojet2026-les-2-mousquetaires/public/img/
```

### 3. Adapter`base.html.twig`

```twig
<!-- Dans <head> -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<!-- Dans <body> -->
<script src="{{ asset('js/main.js') }}"></script>
```

### 4. Tester

```bash
symfony server:start
# → Accédez à http://localhost:8000
```

---

## 💡 Conseil

**Plus rapide** : Gardez le Bootstrap 5 actuel et ajoutez juste le CSS du template pour les personnalisations :

```bash
# Fusionner les CSS
cp template/css/custom.css public/css/theme-additions.css

# Importer dans base.html.twig
<link rel="stylesheet" href="{{ asset('css/custom-overrides.css') }}">
<link rel="stylesheet" href="{{ asset('css/theme-additions.css') }}">
```

Ça vous laisse flexibilité pour changer de template à l'avenir ! 🚀

---

**🎨 Bon themeing !**
