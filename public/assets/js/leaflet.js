class Leaflet {
	static map = null;

	/** Initialise la carte Leaflet. */
	static initMap() {
		Leaflet.map = L.map('map').setView([51.505, -0.09], 13);
		
		L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			maxZoom: 18,
			id: 'mapbox/streets-v11',
			tileSize: 512,
			zoomOffset: -1,
			accessToken: 'pk.eyJ1IjoieW91bGFjIiwiYSI6ImNreDA0YzZ1dzBubGEydHB6ZzJtZHJqaWwifQ.D5slRhh0SpY8Cy0LO6N0Hg'
		}).addTo(Leaflet.map);
	}

	static setOnClick() {
		Leaflet.map.on('click', function(e) {
			alert("Lat, Lon : " + e.latlng.lat + ", " + e.latlng.lng)
		});
	}

}