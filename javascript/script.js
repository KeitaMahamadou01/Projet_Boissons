function actionFavori(nomRecette,nomUtilisateur,etatFavori) {
    $.ajax({
        url:"../données/actionFavori.php",
        type: "post",
        datatype: "json",
        data: {recette: nomRecette, utilisateur: nomUtilisateur, etat: etatFavori},
        success: function(response){
            if(response.success){
                window.location.reload();
            }
        }
    })
}
