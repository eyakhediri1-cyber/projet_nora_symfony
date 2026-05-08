<!-- Template personnalisé Bootstrap 5 pour Les 2 Mousquetaires -->
<!-- À ajouter dans templates/base.html.twig pour personnaliser l'apparence -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{% block title %}🎉 Les 2 Mousquetaires - Événements{% endblock %}</title>
    
    <!-- === OPTION 1 : Bootstrap 5 depuis CDN (actuellement utilisé) === -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- === OPTION 2 : Remplacer par un template personnalisé === -->
    <!-- Après télécharger un template de https://themewagon.com/ :
    <link rel="stylesheet" href="{{ asset('css/theme-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-variables.css') }}">
    -->
    
    <!-- === OPTION 3 : Ajouter des CSS personnalisées AVEC Bootstrap === -->
    <link rel="stylesheet" href="{{ asset('css/custom-overrides.css') }}">
</head>
<body>
    {# Navigation avec personnalisation #}
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <!-- Modification facile via custom-overrides.css -->
    </nav>

    {# Contenu principal #}
    <main class="custom-main">
        <!-- Le reste du contenu -->
    </main>

    {# Footer personnalisé #}
    <footer class="custom-footer">
        <!-- Footer du template -->
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés du template -->
    <script src="{{ asset('js/theme.js') }}"></script>
</body>
</html>
