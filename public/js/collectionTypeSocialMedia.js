// Scripts jQuery / JavaScript généraux
$(document).ready(function() { // Une fois que le document (base.html.twig) HTML/CSS a bien été complètement chargé...
    
    // add-collection-widget.js : fonction permettant d'ajouter un nouveau bloc "socialMediaAccount" au sein d'une player (pour agrandir la collection)
    $('.add-another-collection-widget').click(function (e) {
        
        var list = $($(this).attr('data-list-selector'))
        
        // Récupération du nombre actuel d'élément "socialMediaAccount" dans la collection (à défaut, utilisation de la longueur de la collection)
        var counter = list.data('widget-counter') || list.children().length
        
        // Récupération de l'identifiant du joueur concerné, en cours de création/modification
        var player = list.data('player')
        
        // Extraction du prototype complet du champ (que l'on va adapter ci-dessous)
        var newWidget = list.attr('data-prototype')
        
        // Remplacement des séquences génériques "__name__" utilisées dans les parties "id" et "name" du prototype
        // par un numéro unique au sein de la collection de "socialMediaAccounts" : ce numéro sera la valeur du compteur
        // courant (équivalent à l'index du prochain champ, en cours d'ajout).
        // Au final, l'attribut ressemblera à "player[socialMediaAccounts][n°]"
        newWidget = newWidget.replace(/__name__/g, counter)
        
        // Ajout également des attributs personnalisés "class" et "value", qui n'apparaissent pas dans le prototype original 
        newWidget = newWidget.replace(/><input type="hidden"/, ' class="borders"><input type="hidden" value="'+player+'"')
        
        // Incrément du compteur d'éléments et mise à jour de l'attribut correspondant
        counter++
        list.data('widget-counter', counter)

        // Création d'un nouvel élément (avec son bouton de suppression), et ajout à la fin de la liste des éléments existants
        var newElem = $(list.attr('data-widget-tags')).html(newWidget)
        addDeleteLink($(newElem).find('div.borders'))
        newElem.appendTo(list)
    })

    // anonymize-collection-widget.js : fonction permettant de supprimer un bloc "socialMediaAccount" existant au sein d'un joueur
    $('.remove-collection-widget').find('div.borders').each(function() {
        addDeleteLink($(this))
    })

    // fonction permettant l'ajout d'un bouton "Supprimer ce module" dans un bloc "socialMediaAccount", et d'enregistrer l'évenement "click" associé
    function addDeleteLink($unitForm) {
        
        var $removeFormButton = $('<div class="block"><button type="button" class="button">Supprimer ce module</button></div>');
        $unitForm.append($removeFormButton)
    
        $removeFormButton.on('click', function(e) {
            $unitForm.remove()
        })

    }

    // remove-player.js : fonction permettant de demander la confirmation de suppression d'un joueur
    $('.remove-player-confirm').on('click', function(e) {
        
        e.preventDefault()
        let id=$(this).data('id')
        let href=$(this).attr('href')
        showModalConfirm(id, href, "Confirmation de suppression d'un joueur")

    })

    // remove-stagiaire.js : fonction permettant de demander la confirmation de suppression d'un stagiaire
    $('.remove-stagiaire-confirm').on('click', function(e) {
        
        e.preventDefault()
        
        let id=$(this).data('id')
       
        let href=$(this).attr('href')
        
        showModalConfirm(id, href, "Confirmation de suppression d'un stagiaire")

    })

    // anonymize-stagiaire.js : fonction permettant de demander la confirmation d'anonymisation d'un stagiaire
    $('.anonymize-stagiaire-confirm').on('click', function(e) {
        
        e.preventDefault()
        
        let id=$(this).data('id')
        
        let href=$(this).attr('href')
        
        showModalConfirm(id, href, "Confirmation de l'anonymisation d'un stagiaire")

    })

    // Fonction permettant l'affichage de la fenêtre modale de confirmation pour chaque situation
    function showModalConfirm($id, $href, $title) {
        
        console.log("id   = "+$id)
        
        console.log("href = "+$href)
        
        $('#modalPopup .modal-title').html($title)
       
        $('#modalPopup .modal-body').html("<span class='center'><i class='fas fa-spinner fa-spin fa-4x'></i></span>")
        
        $.get(
            
            "confirm", // La route doit toujours être accessible au moyen du chemin "confirm" dans le contrôleur associé à l'entité concernée 
            {
                'id' : $id
            },
            function(resView) {
                $('#modalPopup .modal-body').html(resView)
            }

        )
        
        $('#modalConfirm').on('click', function(e){
            window.location.href = $href
        })

        $('#modalPopup').modal('show')
    }

    
})



// Function to limit the checked box to two
$(document).ready(function () {

    // we take the checkboxes of the class country-selected 
    $('.country-selected :checkbox').on('change', function () {

        // Count the number of selected options
        var selectedCount = $('.country-selected :checkbox:checked').length;

        console.log(selectedCount);

        // If the limit is reached, uncheck the last selected option
        if (selectedCount > 2) {

            $(this).prop('checked', false);

        }
    });
});
