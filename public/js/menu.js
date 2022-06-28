/**
 * Chargement du DOM
 */
 window.onload = function() {
    alert("Document ready!");
}
/**
 * @param{boolean} response
 */

/**
 * Menu Burger
 */
const open = document.getElementById("open");         // 1élément//
const burger = document.getElementById("burger");     // 2élément//
const ul = document.querySelector("ul");              // 3élément//

open.addEventListener("click", function(event) {
    event.preventDefault()
    ul.classList.toggle("open")
});
