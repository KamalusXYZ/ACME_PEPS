"use strict";

const ERR_EMPTY_FILE = "Fichier vide."
const ERR_FILE_SIZE_EXCEEDS_LIMIT = "Fichier trop lourd."
const ERR_WRONG_MIME_TYPE = "Type de fichier invalide."

// Récupérer une rérérence à l'élément thumbnail.
let thumbnail = document.querySelector('#thumbnail > img')

/*
 * Charger la photo en drga'n drop le cas échéant.
 */

//Supprimer le comportement par défaut du drag over.
thumbnail.addEventListener('dragover', evt => evt.preventDefault());

// Ecouter le drop.
thumbnail.addEventListener('drop', evt => {
    evt.preventDefault();
    // Transferer les données à l'élément INPUT.
     document.form1.photo.files = evt.dataTransfer.files;
     // Déléguer à displayPhoto().
     displayPhoto(evt.dataTransfer.files)

});

/**
 *Récuperer et vérifier les photos choisie.
 * Si valide, affiche la photo.
 * Retourner true systématiquement pour un comportement correct en drag'n drop.
 *
 * @param {FileList} files
 */
function displayPhoto(files) {
    // Si pas de fileList ou FilesList vide, abandonner.

    if (!files || files.length !== 1)
        return ;
    // Récupérer le File en premier élément du FileList.
    let file = files[0];
    // Si le fichier est vide abandonner.
    if (!file.size)
        return  alert(ERR_EMPTY_FILE);

    // Si le fichier est trop lourd ,abandonner.
    if (file.size > IMG_MAX_FILE_SIZE) {
        return alert(ERR_FILE_SIZE_EXCEEDS_LIMIT);

    }
    // Si le type MIME du fichier est incorrect, abandonner.
    if (IMG_ALLOW_MIME_TYPES.length && !IMG_ALLOW_MIME_TYPES.includes(file.type))
        return alert(ERR_WRONG_MIME_TYPE);


    //Afficher l'image.
    thumbnail.src = URL.createObjectURL(file);


}
