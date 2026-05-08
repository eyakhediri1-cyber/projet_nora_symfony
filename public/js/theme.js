// Les 2 Mousquetaires - Theme JavaScript
// Scripts de base pour interactions du site

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // === GESTION DES ALERTES FLASH ===
    // Fermer les alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                alert.style.animation = 'slideUp 0.3s ease forwards';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
    });

    // === VALIDATION DES FORMULAIRES ===
    // Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // === CONFIRMATION AVANT SUPPRESSION ===
    // Les confirmations sont gérées directement dans les templates via onsubmit

    // === ANIMATIONS PROGRESSBAR ===
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease';
            bar.style.width = width;
        }, 100);
    });

    // === HOVER EFFECTS SUR LES CARTES ===
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // === TOOLTIP BOOTSTRAP (si nécessaire) ===
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    console.log('🎉 Les 2 Mousquetaires - Theme loaded successfully!');
});

// Animation de fermeture des alertes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);
