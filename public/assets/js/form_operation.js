/** Propose des fonction uniquement pour le formulaire des opérations. */
class FormOperation {

	/**
	 * Permet de vérifier que le département existe.
	 * @param {boolean} nullable Indique que la valeur peut être vide ou non.
	 */
	static checkDepartementExist(nullable = false) {
		/** @type {HTMLButtonElement} */
		const field = document.getElementById("form_departement");
		if (nullable && field.value == "") field.setCustomValidity("");
		else {
			checkValueExist(
				"commune",
				[["departement", "=", field.value]],
				() => {
					field.setCustomValidity("");
					document.getElementById("form_commune").oninput();
				},
				() => { field.setCustomValidity("Le departement n'existe pas."); }
			);
		}
	}

	/**
	 * Permet de vérifier que le département existe.
	 * @param {boolean} nullable Indique que la valeur peut être vide ou non.
	 */
	static checkCommuneExist(nullable = false) {
		/** @type {HTMLButtonElement} */
		const fieldCom = document.getElementById("form_commune");
		/** @type {HTMLButtonElement} */
		const fieldDep = document.getElementById("form_departement");
		
		if (nullable && fieldCom.value == "") fieldCom.setCustomValidity("");
		else {
			let where = [["nom", "=", fieldCom.value]];
			if (fieldDep.value != "" && fieldDep.validity.valid) where.push(["departement", "=", fieldDep.value]);
			
			checkValueExist(
				"commune",
				where,
				() => { fieldCom.setCustomValidity(""); },
				() => { fieldCom.setCustomValidity("Le departement n'existe pas."); }
			);
		}
	}

	/** Met à jour l'affichage de l'input de l'organisme en fonction de si il existe dans la BDD. */
	static checkOrganismeExist() {
		const input = document.getElementById("form_organisme");
		checkValueExist("organisme", [["nom", "=", input.value]], 
			() => { input.setCustomValidity(""); },
			() => { input.setCustomValidity("L'organisme n'existe pas."); }
		);
	}

	/** Ajoute un organisme à la BDD. */
	static addOrganisme() {
		const input = document.getElementById("form_organisme");
		$.ajax({
			type: "POST",
			url: "https://archeohandi.huma-num.fr/public/fonction/add_organisme",
			data: { name: input.value },
			success: function (response) {
				input.setCustomValidity("");
			}
		});
	}

	/** Prépare tous ce qu'il faut pour la carte. */
	static prepareMap() {
		Leaflet.initMap("map");

		const inputLon = document.getElementById("form_X");
		const inputLat = document.getElementById("form_Y");
		// const inputDep = document.getElementById("form_departement");
		// const inputCom = document.getElementById("form_commune");
		// const inputAddr = document.getElementById("form_adresse");
		Leaflet.setOnClick(result => {
			console.log(result);
			inputLon.value = result.latlng.lng;
			inputLat.value = result.latlng.lat;
			// inputDep.value = result.address.Subregion;
			// inputCom.value = result.address.City;
			// inputAddr.value = result.address.Address;
			this.updateCoordinate();
		})

		this.updateCoordinate();
	}

	/** Met à jour le marqueur de la carte en fonction de la valeur des champs. */
	static updateCoordinate() {
		const inputLon = document.getElementById("form_X");
		const inputLat = document.getElementById("form_Y");
		// En cas d'informations manquantes
		if (inputLon.value == "" || inputLat.value == "") return;
		
		Leaflet.setMarker([Number(inputLat.value), Number(inputLon.value)]);
	}
}