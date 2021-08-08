import Swal from "sweetalert2";

export function initSweetAlert() {
    let deleteFormsElements: NodeListOf<HTMLFormElement> = document.querySelectorAll('.delete');
    deleteFormsElements?.forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Suppression',
                text: 'Voulez-vous vraiment supprimer ce paiement',
                icon: 'warning',
                showCancelButton: true,
                iconHtml:'<span class="material-icons">update</span>',
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if(result.isConfirmed) {
                    location.pathname = '/fr/paiement/delete/' + form.dataset.id;
                }
            });
        })
    })
}



