/** Contient des fonctions pour la recherche. */
class Search {
	/** @type {string|null} Id de la balise contenant les données. */
	static _dataId = null;
	/** @type {Map<number, SearchResult>|null} Toutes les données des opérations et sujets. */
	static _data = null

	/**
	 * Défini l'id de l'élément contenant toutes les données des opérations et sujets.
	 * @param {string} dataId
	 */
	static setDataId(dataId) {
		this._dataId = dataId;
	}

	/**
	 * Export les données en CSV.
	 */
	static exportToCSV() {
		if (this._data === null) this._loadData();

		let csv = "";
		const operationFields = Operation.getFields();
		const subjectFields = Subject.getFields();

		// Ajout des noms en titre
		csv += `Opérations`;
		for (let i=0; i<operationFields.length; i++) csv += `;`;
		csv += `Sujets handicapés;\n`;

		// Ajout des titres des opérations
		for (const field of Operation.getFields()) {
			csv += `${field};`;
		}
		// Ajout des titres des sujets
		for (const field of Subject.getFields()) {
			csv += `${field};`;
		}
		csv += "\n";

		for (const [id, result] of Search._data.entries()) {
			// Ajout de l'opération
			for (const field of operationFields) {
				csv += `"${this._reformatData(result.operation[field])}";`;
			}

			// Ajout des sujets
			for (const subject of result.subjects) {
				if (csv[csv.length-1] === "\n") {
					// Ajout espacement pour aligner avec les autres sujets
					for (let i = 0; i<operationFields.length; i++) csv += ";";
				}

				// Ajout données du sujet
				for (const field of subjectFields) {
					csv += `"${this._reformatData(subject[field])}";`;
				}
				csv += "\n";

			}

			csv += "\n";
		}

		Helper.exportCSV(csv, "resultat_recherche.csv");
	}

	/**
	 * Met dans un format plus français les données si besoin.
	 * @param {string} data 
	 */
	static _reformatData(data) {
		switch (data) {
			case null: return "";
			case true: return "oui";
			case false: return "non";
			default: {
				if (typeof(data) === "string") {
					return data.replaceAll(`"`, `""`);
				}
				return data;
			}
		}
	}

	/** Charge les données. */
	static _loadData() {
		// Récupération des données
		const container = document.getElementById(this._dataId);
		const content = container.innerHTML;
		const json = JSON.parse(content);

		// Transformation en objets
		this._data = new Map();
		for (let [idOp, pair] of Object.entries(json)) {
			idOp = Number(idOp);

			// Chargement operation
			const operation = new Operation(pair["operation"]);

			// Chargement sujets
			const subjects = [];
			for (const [idSub, subData] of Object.entries(pair["subjects"])) {
				subjects.push(new Subject(subData));
			}

			let result = new SearchResult(operation, subjects)
			this._data.set(idOp, result);
		}

	}
}