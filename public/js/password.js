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
        showBtn.innerHTML = "Cacher mot de passe"
    } else {
        inputBox.type = "password";
        showBtn.innerHTML = "Afficher password"
    }     
});

/**
 * sélectionnons les éléments.
 * Nous sélectionnons les deux classes créées par 
 * unpkg / feather et l’input de type password pour 
 * ensuite pouvoir détecter les clics et transformer le type de l’input.
 */

 const eye = document.querySelector(".fa-eye");
 const eyeoff = document.querySelector(".fa-eye-slash");
 const passwordField = document.querySelector("input[type=password]");

 /**
  * le clic sur l'oeil et sur l'oeil barré.
  * Lorsque l’utilisateur vient cliquer sur l’oeil, 
  * alors on cache l’icône de l’oeil “normale” et on 
  * affiche l’icône de l’oeil “barré”. Nous en profitons 
  * pour changer le type de l’input de “password” à “text”.
  * Enfin, nous faisons l’inverse pour pouvoir revenir à un input “caché”.
  */

  eye.addEventListener("click", () => {
    eye.style.display = "none";
    eyeoff.style.display = "block";
    passwordField.type = "text";
  });
  
  eyeoff.addEventListener("click", () => {
    eyeoff.style.display = "none";
    eye.style.display = "block";
    passwordField.type = "password";
  });