"use strict";


function deleteAll(idProduct, name){

   if (confirm(`Vraiment supprimer le produit ${name}`))
        location.href = `/product/delete/${idProduct}/all`;

}

function deleteImg(idProduct, name){

    if (confirm(`Vraiment supprimer les images du produit ${name}`))
        location.href = `/product/delete/${idProduct}/img`;


}