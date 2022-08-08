/**
 * Affiche/cache le mot de passe
 */

// Selectioner le button par la classe "show-box"
const showBtn = document.querySelector('.show-box'),

    // Appeler input par son id
    inputBox = document.querySelector('#inputPassword');

// Applique un écouteur d'évenement à chaque éléments récupérérs
showBtn.addEventListener('click', () => {
    
    if(inputBox.type === "password" ) {
        inputBox.type = "text";
        showBtn.innerHTML = "Cacher mot de passe"
    } else {
        inputBox.type = "password";
        showBtn.innerHTML = "Afficher mot de passe"
    }     
});