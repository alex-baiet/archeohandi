
/** Permet de faire une recherche rapide sur le tableau des opérations. */
class FastSearch {

	/** @type {SearchResult[]} lines */
	static lines;

	static init(lines) {
		console.log(lines);
		FastSearch.lines = lines;
	}

	/**
	 * 
	 * @param {HTMLInputElement} input 
	 */
	static search(input) {
		const value = input.value;
		for (const line of FastSearch.lines) {
			/** @type {HTMLTableRowElement} Ligne de l'opération */
			const rowOp = document.getElementById(`row_op_${line.operation.id}`);
			/** @type {HTMLTableRowElement} Ligne des sujets */
			const rowSu = document.getElementById(`row_subjects_${line.operation.id}`);

			const strings = ["commune", "departement", "region", "adresse", "organisme", "type_operation", "ea", "oa", "patriarche", "numero_operation", "arrete_prescription", "responsable"];
			const numbers = ["id", "annee", "X", "Y"];

			let valid = false;

			// Vérification entré utilisateur correspondant à des opérations
			if (value.length === 0) valid = true;
			if (!valid) {
				for (const str of strings) {
					const field = line.operation[str];
					if (field !== null && field.includes(value)) {
						valid = true;
						break;
					}
				}
			}
			if (!valid) {
				for (const num of numbers) {
					const field = line.operation[num];
					if (field == value) {
						valid = true;
						break;
					}
				}
			}

			// Maj affichage ligne de l'opération
			if (valid) {
				// recherche correspondant
				rowOp.style.display = "table-row";
			} else {
				// recherche non compatible
				rowOp.style.display = "none";
				rowSu.style.display = "none";
			}
		}
	}
	
	/**
	 * 
	 * @param {*} field Champ de l'opération a tester
	 * @param {string} value Valeur entrée par l'utilisateur
	 * @param {string} type Type du champ de l'opération
	 * @returns {boolean}
	 */
	static __checkValue(field, value, type) {
		if (value == null) return true;
		if (field == null) return false;
		if (type === "string") return field.includes(value);
		if (type === "number") return field == value;
		throw new Error(`type "${type}" n'est pas pris en charge.`);
	}
}
