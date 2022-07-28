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
		"ea",
		"oa",
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

	/** @type {?number} */ id = null;
	/** @type {?number} */ annee = null;
	/** @type {?number} */ id_commune = null;
	/** @type {?string} */ commune = null;
	/** @type {?string} */ departement = null;
	/** @type {?string} */ region = null;
	/** @type {?string} */ adresse = null;
	/** @type {?number} */ X = null;
	/** @type {?number} */ Y = null;
	/** @type {?number} */ id_organisme = null;
	/** @type {?string} */ organisme = null;
	/** @type {?number} */ id_type_op = null;
	/** @type {?string} */ type_operation = null;
	/** @type {?string} */ ea = null;
	/** @type {?string} */ oa = null;
	/** @type {?string} */ patriarche = null;
	/** @type {?string} */ numero_operation = null;
	/** @type {?string} */ arrete_prescription = null;
	/** @type {?string} */ responsable = null;
	/** @type {?string} */ anthropologues = null;
	/** @type {?string} */ paleopathologistes = null;
	/** @type {?string} */ bibliographie = null;
	/** @type {?string} */ date_ajout = null;
	/** @type {?bool}   */ complet = null;

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
		this.mergeValue(data, "ea", "ea", Archeo.STRING);
		this.mergeValue(data, "oa", "oa", Archeo.STRING);
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