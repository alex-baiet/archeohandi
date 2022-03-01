class Leaflet {
	static map = null;
  static geocodeService = null;
	static marker = null;

	/**
	 * Initialise la carte Leaflet.
	 * @param {string} id Identifiant de la div cible.
	 */
	static initMap(id) {
		// Initialisation de la carte
		Leaflet.map = L.map(id).setView([47, 2], 5);
		var southWest = L.latLng(41.0594733607425, -5.625),
    northEast = L.latLng(51.2446271894719, 8.96447621125652),
    bounds = L.latLngBounds(southWest, northEast);

		L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			maxZoom: 18,
			minZoom: 5,
			id: 'mapbox/streets-v11',
			tileSize: 512,
			zoomOffset: -1,
			accessToken: 'pk.eyJ1IjoieW91bGFjIiwiYSI6ImNreDA0YzZ1dzBubGEydHB6ZzJtZHJqaWwifQ.D5slRhh0SpY8Cy0LO6N0Hg'
		}).addTo(Leaflet.map);

		this.map.setMaxBounds(bounds);
		document.getElementsByClassName("leaflet-control-zoom")[0].style.display = "none";

		// Initialisation geocodeService
		// Leaflet.geocodeService = L.esri.Geocoding.geocodeService({ apikey: "AAPKe9e63b0e7f4048e6bff53201d20b4b92Z5BraV8Ow8Qvorsmc4WsR3stO83QGD3tXEUKZZexGfR-MyLf-F9NRZz3eQs9miYI" })

	}

	/**
	 * Permet de définir une action à effectuer lors du clic sur la carte.
	 * @param {(result) => {}} action Position : result.latlng.lng, result.latlng.lat
	 */
	static setOnClick(action) {
		let a = []
		Leaflet.map.on('click', function(e) {
			action(e);
			// while (e.latlng.lng < -180) e.latlng.lng += 360;
			// while (e.latlng.lng > 180) e.latlng.lng -= 360;
			// Leaflet.geocodeService.reverse().latlng(e.latlng).run(function (error, result) {
			// 	// Gestion de l'erreur
			// 	if (error) {
			// 		console.error(`Une erreur est survenu lors du click sur la carte`);
			// 		console.error(e.latlng);
			// 		console.error(error);
			// 		return;
			// 	}

			// 	// Exécution de l'action a effectuer
			// 	action(result);
			// })
		});
	}

	/**
	 * met à la position indiquer le marqueur
	 * @param {number[]} position Position au format [latitude, longitude]
	 */
	static setMarker(position) {
		// Suppression de l'ancien marqueur
		if (Leaflet.marker !== null) {
			Leaflet.marker.remove();
		}

		// Ajout du nouveau marqueur
		Leaflet.marker = L.marker(position);
		Leaflet.marker.addTo(Leaflet.map);
	}

}