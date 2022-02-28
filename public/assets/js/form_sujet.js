
/** Contient des fonction uniquement pour le formulaire des sujets. */
class FormSujet {
	/**
	 * Génère une description rapide à copier pour Nakala.
	 * @param {string} idOutput Id de la balise devant contenir la descrption.
	 */
	static generateDescription(idOutput) {
		let txt = "";
		const out = document.getElementById(idOutput);

		// Ajout adresse
		const inputCom = document.getElementById("form_depot_commune");
		const inputAddr = document.getElementById("form_depot_adresse");
		txt += `Adresse : ${inputAddr.value}, ${inputCom.value}`;

		// Ajout type de sepulture
		/** @type {HTMLSelectElement} */
		const inputSep = document.getElementById("form_id_sepulture");
		txt += `\nType de sepulture : ${inputSep.options[inputSep.selectedIndex].text}`;

		// Ajout diagnostique
		txt += `\nDiagnostique :`
		let i = 0;
		while (i < 20) { // VÉRIFICATION FIX, A CHANGER
			i++;
			/** @type {HTMLInputElement} */
			const inputDia = document.getElementById(`form_diagnostic_${i}`);
			if (inputDia === null) continue;
			if (inputDia.checked) {
				// Ajout du label
				const labelDia = document.getElementById(`form_diagnostic_label_${i}`);
				txt += `\n - ${labelDia.innerText}`;
			}
		}

		i = 0;
		while (i < 20) { // VÉRIFICATION FIX, A CHANGER
			i++;
			/** @type {HTMLInputElement} */
			const inputPat = document.getElementById(`form_pathologies_${i}`);
			if (inputPat === null) continue;
			if (inputPat.checked) {
				// Ajout du label
				const labelPat = document.getElementById(`form_pathologies_label_${i}`);
				txt += `\n - ${labelPat.innerText}`;
			}
		}

		out.innerText = txt;
	}
}