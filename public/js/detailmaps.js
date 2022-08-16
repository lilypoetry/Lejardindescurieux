// affiche les addresse de BD market

let mymap // Variable qui permettra de stocker la carte

// On attend que le DOM soit chargé
window.onload = () => {
    // Nous initialisons la carte et nous la centrons sur Paris
    mymap = L.map('detailsMap').setView([48.852969, 2.349903], 11)
    // L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'Carte fournie par Wikimedia',
        minZoom: 1,
        maxZoom: 20
    }).addTo(mymap)
}

// On écoute le clic sur la carte et on lance la fonction "mapClickListen"
mymap.on('click', mapClickListen)

/**
 * Cette fonction se déclenche au clic, crée un marqueur et remplit les champs latitude et longitude
 * @param {event} e 
 */
 function mapClickListen(e) {
    // On récupère les coordonnées du clic
    pos = e.latlng

    // On crée un marqueur
    addMarker(pos)

    // On affiche les coordonnées dans le formulaire
    document.querySelector("#lat").value=pos.lat
    document.querySelector("#lon").value=pos.lng
}

/**
 * Ajoute un marqueur sur la carte
 * @param {*} pos 
 */
 function addMarker(pos){
    // On vérifie si le marqueur existe déjà
    if (marqueur != undefined) {
        // Si oui, on le retire
        mymap.removeLayer(marqueur);
    }

    // On crée le marqueur aux coordonnées "pos"
    marqueur = L.marker(
        pos, {
            // On rend le marqueur déplaçable
            draggable: true
        }
    )

    // On ajoute le marqueur
    marqueur.addTo(mymap)
}

/**
 * Ajoute un marqueur sur la carte
 * @param {*} pos 
 */
 function addMarker(pos){
    // On vérifie si le marqueur existe déjà
    if (marqueur != undefined) {
        // Si oui, on le retire
        macarte.removeLayer(marqueur);
    }

    // On crée le marqueur aux coordonnées "pos"
    marqueur = L.marker(
        pos, {
            // On rend le marqueur déplaçable
            draggable: true
        }
    )

    // On écoute le glisser/déposer et on met à jour les coordonnées
    marqueur.on('dragend', function(e) {
        pos = e.target.getLatLng();
        document.querySelector("#lat").value=pos.lat;
        document.querySelector("#lon").value=pos.lng;
    });

    // On ajoute le marqueur
    marqueur.addTo(mymap)
}

/**
 * Récupérer les coordonnées de l'adresse et placer le marqueur
 */
 function getCity(){
    // On "fabrique" l'adresse complète (des vérifications préalables seront nécessaires)
    let adresse = document.querySelector("#adresse").value + ", " + document.querySelector("#cp").value+ " " + document.querySelector("#ville").value;

    // On initialise la requête Ajax
    const xmlhttp = new XMLHttpRequest

    // On détecte les changements d'état de la requête
    xmlhttp.onreadystatechange = () => {
        // Si la requête est terminée
        if(xmlhttp.readyState == 4){
            // Si nous avons une réponse
            if(xmlhttp.status == 200){
                // On récupère la réponse
                let response = JSON.parse(xmlhttp.response)

                // On récupère la latitude et la longitude
                let lat = response[0]['lat']
                let lon = response[0]['lon']

                // On écrit les valeurs dans le formulaire
                document.querySelector("#lat").value= lat;
                document.querySelector("#lon").value= lon;

                // On crée le marqueur
                pos = [lat, lon];
                addMarker(pos);

                // On centre la carte sur l'adresse
                mymap.setView(pos, 11);
            }
        }
    }

    // On ouvre la requête
    xmlhttp.open('get', `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&addressdetails=1&limit=1&polygon_svg=1`)

    // On envoie la requête
    xmlhttp.send();
   
}