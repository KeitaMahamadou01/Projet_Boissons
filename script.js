function actionFavori(nomRecette,nomUtilisateur,etatFavori) {
    $.ajax({
        url:"actionFavori.php",
        type: "post",
        datatype: 'json',
        data: {recette: nomRecette, utilisateur: nomUtilisateur, etat: etatFavori},
        success: function(response){
            console.log("Réponse AJAX :", response);
            if(response.success){
                window.location.reload();
            }
        }
    })
}
