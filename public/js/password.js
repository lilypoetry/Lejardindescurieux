/**
 * Affiche/cache le mot de passe
 */

// Selectioner le button par la classe et balise a
const showBtn = document.querySelector('.show-box a'),

    // Appeler input par son id
    inputBox = document.querySelector('#inputPassword');

showBtn.addEventListener('click', () => {
    
    if(inputBox.type === "password" ) {
        inputBox.type = "text";
        showBtn.innerHTML = "Cacher password"
    } else {
        inputBox.type = "password";
        showBtn.innerHTML = "Afficher password"
    }     
});