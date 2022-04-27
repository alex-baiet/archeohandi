class Leaflet {
	static map = null;
	static marker = null;
	static circle = null;
  static _geocodeService = null;

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
		// Leaflet._geocodeService = L.esri.Geocoding.geocodeService({ apikey: "AAPKe9e63b0e7f4048e6bff53201d20b4b92Z5BraV8Ow8Qvorsmc4WsR3stO83QGD3tXEUKZZexGfR-MyLf-F9NRZz3eQs9miYI" })

	}

	/**
	 * Permet de définir une action à effectuer lors du clic sur la carte.
	 * @param {string} event Nom de l'évènement. https://leafletjs.com/SlavaUkraini/reference.html#map-interaction-events List of all events
	 * @param {(result) => {}} action Position : result.latlng.lng, result.latlng.lat
	 */
	static setEventHandler(event, action) {
		let a = []
		Leaflet.map.on(event, function(e) {
			action(e);
		});
	}

	/** Ajoute un marqueur à la position indiquée sur la carte. */
	static addMarker(lat, lng, title=null) {
		const newMarker = L.marker([lat, lng]);
		newMarker.addTo(Leaflet.map);
		if (title !== null) {
			newMarker.bindPopup(title);
		} 
		// Pour pouvoir les éditer, il faut d'abord les stocker
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

	/**
	 * Met à jour le cercle, ou le créer si il n'existe pas.
	 */
	static updateCircle(lat, lng, radius) {
		if (this.circle !== null) {
			// Maj de l'ancien cercle
			const ll = L.latLng(lat, lng);
			this.circle.setRadius(radius);
			this.circle.setLatLng(ll);

		} else {
			// Création d'un nouveau cercle
			this.circle = L.circle([lat, lng], { radius: radius });
			this.circle.addTo(this.map);
		}

	}	

}