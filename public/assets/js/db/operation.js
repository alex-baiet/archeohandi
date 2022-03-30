class Operation extends Archeo {
	/** Liste ordonnée des champs disponible. */
	static _fields = [
		"id",
		"annee",
		"commune",
		"departement",
		"region",
		"adresse",
		"X",
		"Y",
		"organisme",
		"type_operation",
		"EA",
		"OA",
		"patriarche",
		"numero_operation",
		"arrete_prescription",
		"responsable",
		"anthropologues",
		"paleopathologistes",
		"bibliographie",
		"date_ajout",
		"complet",
	];

	id = null;
	annee = null;
	id_commune = null;
	commune = null;
	departement = null;
	region = null;
	adresse = null;
	X = null;
	Y = null;
	id_organisme = null;
	organisme = null;
	id_type_op = null;
	type_operation = null;
	EA = null;
	OA = null;
	patriarche = null;
	numero_operation = null;
	arrete_prescription = null;
	responsable = null;
	anthropologues = null;
	paleopathologistes = null;
	bibliographie = null;
	date_ajout = null;
	complet = null;

	/**
	 * Construit l'opération en fonction des données indiquées.
	 * @param {Object} data
	 */
	constructor(data) {
		super();
		this.mergeValue(data, "id", "id", Archeo.NUMBER);
		this.mergeValue(data, "annee", "annee", Archeo.NUMBER);
		this.mergeValue(data, "id_commune", "id_commune", Archeo.NUMBER);
		this.mergeValue(data, "commune", "commune", Archeo.NUMBER);
		this.mergeValue(data, "departement", "departement", Archeo.NUMBER);
		this.mergeValue(data, "region", "region", Archeo.NUMBER);
		this.mergeValue(data, "adresse", "adresse", Archeo.STRING);
		this.mergeValue(data, "X", "X", Archeo.NUMBER);
		this.mergeValue(data, "Y", "Y", Archeo.NUMBER);
		this.mergeValue(data, "id_organisme", "id_organisme", Archeo.NUMBER);
		this.mergeValue(data, "organisme", "organisme", Archeo.STRING);
		this.mergeValue(data, "id_type_op", "id_type_op", Archeo.NUMBER);
		this.mergeValue(data, "type_operation", "type_operation", Archeo.STRING);
		this.mergeValue(data, "EA", "EA", Archeo.STRING);
		this.mergeValue(data, "OA", "OA", Archeo.STRING);
		this.mergeValue(data, "patriarche", "patriarche", Archeo.STRING);
		this.mergeValue(data, "numero_operation", "numero_operation", Archeo.STRING);
		this.mergeValue(data, "arrete_prescription", "arrete_prescription", Archeo.STRING);
		this.mergeValue(data, "responsable", "responsable", Archeo.STRING);
		this.mergeValue(data, "anthropologues", "anthropologues", Archeo.STRING);
		this.mergeValue(data, "paleopathologistes", "paleopathologistes", Archeo.STRING);
		this.mergeValue(data, "bibliographie", "bibliographie", Archeo.STRING);
		this.mergeValue(data, "date_ajout", "date_ajout", Archeo.STRING);
		this.mergeValue(data, "complet", "complet", Archeo.BOOLEAN);
	}

	/** @return {array} Liste ordonnée des champs disponible. */
	static getFields() { return [...Operation._fields]; }

}