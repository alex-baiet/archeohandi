class Operation extends Archeo {
	/**  */
	static _fields = ["id"]

	id = null;

	/**
	 * Construit l'opération en fonction des données indiquées.
	 * @param {Object} data
	 */
	constructor(data) {
		super();
		let b = {};
		this.mergeValue(data, "id", "id", Archeo.NUMBER);
	}

	/** @return {array} Liste ordonnée des champs disponible. */
	static getFields() { return Operation._fields; }

}