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
		for (const [id, result] of Search._data.entries()) {
			for (const field of Operation.getFields()) {
				csv += `${result.operation[field]};`;
			}
			csv += "\n";
		}

		console.log(`CSV actuel :`);
		console.log(csv)
		Helper.exportCSV(csv, "resultat_recherche.csv");
	}

	/** Charge les données. */
	static _loadData() {
		// Récupération des données
		const container = document.getElementById(this._dataId);
		const content = container.innerHTML;
		const json = JSON.parse(content);
		console.log(json);

		// Transformation en objets
		this._data = new Map();
		for (let [idOp, pair] of Object.entries(json)) {
			idOp = Number(idOp);
			console.log(idOp);

			const operation = new Operation(pair["operation"]);
			const subjects = [];
			let result = new SearchResult(operation, subjects)
			this._data.set(idOp, result);
		}

		console.log(this._data);
	}
}