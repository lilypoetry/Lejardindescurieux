// if ('geolocation' in navigator) {
//   /* geolocation is available */
//   navigator.geolocation.getCurrentPosition((position) => {
//     doSomething(position.coords.latitude, position.coords.longitude);
//   });

//   const watchID = navigator.geolocation.watchPosition((position) => {
//     doSomething(position.coords.latitude, position.coords.longitude);
//   });

//   navigator.geolocation.clearWatch(watchID);

//   function success(position) {
//     doSomething(position.coords.latitude, position.coords.longitude);
//   }
// } else {
//   /* geolocation IS NOT available */
//   function error() {
//     alert('Sorry, no position available.');
//   }

//   const options = {
//     enableHighAccuracy: true,
//     maximumAge: 30000,
//     timeout: 27000
//   };

//   // const watchID = navigator.geolocation.watchPosition(success, error, options);

//   function success(position) {
//     const latitude = position.coords.latitude;
//     const longitude = position.coords.longitude;

//     // Do something with your latitude and longitude
//   }

//   function errorCallback(error) {
//     alert(`ERROR(${error.code}): ${error.message}`);
//   };
// }





function geoFindMe() {

  const status = document.querySelector('#status');
  const mapLink = document.querySelector('#map-link');

  mapLink.href = '';
  mapLink.textContent = '';

  function success(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    status.textContent = '';
    mapLink.href = `https://www.openstreetmap.org/#map=18/${latitude}/${longitude}`;
    mapLink.textContent = `Latitude: ${latitude} °, Longitude: ${longitude} °`;
  }

  function error() {
    status.textContent = 'Unable to retrieve your location';
  }

  if (!navigator.geolocation) {
    status.textContent = 'Geolocation is not supported by your browser';
  } else {
    status.textContent = 'Locating…';
    navigator.geolocation.getCurrentPosition(success, error);
  }

}

// Benoit

function geoloc(){ // ou tout autre nom de fonction
  var geoSuccess = function(position) { // Ceci s'exécutera si l'utilisateur accepte la géolocalisation
      startPos = position;
      userlat = startPos.coords.latitude;
      userlon = startPos.coords.longitude;
      console.log("lat: "+userlat+" - lon: "+userlon);
  };
  var geoFail = function(){ // Ceci s'exécutera si l'utilisateur refuse la géolocalisation
      console.log("refus");
  };
  // La ligne ci-dessous cherche la position de l'utilisateur et déclenchera la demande d'accord
  navigator.geolocation.getCurrentPosition(geoSuccess,geoFail);
}

function cercle(){ // Ou tout autre nom de fonction
  var distance = $("#distance").val(); // Nous récupérons la distance
  $.ajax({
      url:'http://localhost/carte/cherchevilles.php',
      type: 'POST',
      data: 'lat='+userlat+'&lon='+userlon+'&distance='+distance
  }).done(function(reponse){
      var points = JSON.parse(reponse);
      // Ici nous traitons les différents points
  }).fail (function(error){
      console.log(error);
  });
}

document.querySelector('#find-me').addEventListener('click', geoFindMe);

function chercher(){
  var ville = $("#ville").val();
  if(ville != ""){
      $.ajax({
          url: "https://nominatim.openstreetmap.org/search", // URL de Nominatim
          type: 'get', // Requête de type GET
          data: "q="+ville+"&format=json&addressdetails=1&limit=1&polygon_svg=1" // Données envoyées (q -> adresse complète, format -> format attendu pour la réponse, limit -> nombre de réponses attendu, polygon_svg -> fournit les données de polygone de la réponse en svg)
      }).done(function (response) {
          if(response != ""){
              userlat = response[0]['lat'];
              userlon = response[0]['lon'];
          }                
      }).fail(function (error) {
          alert(error);
      });      
  }
}