import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import Swal from 'sweetalert2';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Confirmation globale pour les suppressions
document.addEventListener('submit', function(e) {
    if (e.target.hasAttribute('data-confirm')) {
        e.preventDefault();
        const form = e.target;
        const message = form.getAttribute('data-confirm') || 'Voulez-vous vraiment supprimer cet élément ?';

        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.removeAttribute('data-confirm');
                form.submit();
            }
        });
    }
});
