// https://leafletjs.com/examples/quick-start/
// https://nouvelle-techno.fr/articles/pas-a-pas-inserer-une-carte-openstreetmap-sur-votre-site

// On initialise la latitude et la longitude de Paris (centre de la carte)
var lat = 48.852969;
var lon = 2.349903;
var macarte = null;

// Servira à stocker les groupes de marqueurs
var markerClusters;

// Nous initialisons une liste de marqueurs
var villes = {
    "Paris": { "lat": 48.852969, "lon": 2.349903 },
    "Brest": { "lat": 48.383, "lon": -4.500 },
    "Grenoble": { "lat": 45.1875602, "lon": 5.7357819 },
    "Bayonne": { "lat": 43.500, "lon": -1.467 },
    "Lyon": { "lat": 45.7578137, "lon": 4.8320114 },
    "Nice": { "lat": 43.7009358, "lon": 7.2683912 },
    "Chalon-sur-Saône": { "lat": 46.7888997, "lon": 4.8529605 }
};

// Fonction d'initialisation de la carte
function initMap() {
    // Nous définissons le dossier qui contiendra les marqueurs
    var iconBase = 'http://localhost/carte/icons/';

    // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
    macarte = L.map('map').setView([lat, lon], 11);

    // Nous initialisons les groupes de marqueurs
    markerClusters = L.markerClusterGroup();

    // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous 
    // devons lui préciser où nous souhaitons les récupérer. 
    // Ici, openstreetmap.fr
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        minZoom: 1,
        maxZoom: 20,
        // // Il est toujours bien de laisser le lien vers la source des données
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>'
    }).addTo(macarte);

    // Nous parcourons la liste des villes
    for (ville in villes) {
        // Nous définissons l'icône à utiliser pour le marqueur, sa taille affichée (iconSize), sa position (iconAnchor) et le décalage de son ancrage (popupAnchor)
        var myIcon = L.icon({
            iconUrl: iconBase + "autres.png",
            iconSize: [50, 50],
            iconAnchor: [25, 50],
            popupAnchor: [-3, -76],
        });

        var marker = L.marker([villes[ville].lat, villes[ville].lon], { icon: myIcon }); // pas de addTo(macarte), l'affichage sera géré par la bibliothèque des clusters
        marker.bindPopup(ville);
        markerClusters.addLayer(marker); // Nous ajoutons le marqueur aux groupes
    }
    
}
window.onload = function () {
    // Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
    initMap();
};

// Nous ajoutons un marqueur
var marker = L.marker([lat, lon]).addTo(macarte);

// Fonction d'initialisation de la carte
function initMap() {
    // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
    macarte = L.map('map').setView([lat, lon], 11);
    // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        // Il est toujours bien de laisser le lien vers la source des données
        attribution: 'données © OpenStreetMap/ODbL - rendu OSM France',
        minZoom: 1,
        maxZoom: 20
    }).addTo(macarte);
    // Nous parcourons la liste des villes
    for (ville in villes) {
        var marker = L.marker([villes[ville].lat, villes[ville].lon]).addTo(macarte);
    }
}

// Adapter le zoom de la carte pour afficher automatiquement tous les marqueurs.
// egrouper tous les marqueurs dans un tableau et demander à ce que le zoom s’adapte pour qu’ils soient tous visibles.


