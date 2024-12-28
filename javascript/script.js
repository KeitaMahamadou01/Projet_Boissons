function actionFavori(nomRecette,nomUtilisateur,etatFavori) {
    $.ajax({
        url:"../données/actionFavori.php",
        type: "post",
        datatype: "json",
        data: {recette: nomRecette, utilisateur: nomUtilisateur, etat: etatFavori},
        success: function(response){
            if(response.success){
                window.location=window.location;
            }
        }
    })
}

//On vérifie si une recette se trouve à droite de la page
//quand on a la souris dessus pour pas que le hover la
//fasse sortir de la page
document.addEventListener('DOMContentLoaded', () => {
    const recettes = document.querySelectorAll('.recette');
        
    recettes.forEach(recette => {
        recette.addEventListener('mouseenter', () => {
            const rect = recette.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
        
            if (rect.right + 100 > viewportWidth) {
                recette.classList.add('adroite');
            }
        });
    
        recette.addEventListener('mouseleave', () => {
            recette.classList.remove('adroite');
        });
    });
});